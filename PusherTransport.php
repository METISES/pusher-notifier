<?php

declare(strict_types=1);

namespace Symfony\Component\Notifier\Bridge\Pusher;

use Pusher\Pusher;
use RuntimeException;
use Symfony\Component\Notifier\Exception\LogicException;
use Symfony\Component\Notifier\Exception\UnsupportedMessageTypeException;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\PushMessage;
use Symfony\Component\Notifier\Message\SentMessage;
use Symfony\Component\Notifier\Transport\AbstractTransport;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

/**
 * @author Yasmany Cubela Medina <yasmanycm@gmail.com>
 */
final class PusherTransport extends AbstractTransport
{
    private $pusherClient;

    public function __construct(Pusher $pusherClient, ?HttpClientInterface $client = null, ?EventDispatcherInterface $dispatcher = null)
    {
        $this->pusherClient = $pusherClient;

        parent::__construct($client, $dispatcher);
    }

    public function __toString(): string
    {
        $settings = $this->pusherClient->getSettings();
        preg_match('/api-([\w]+)\.pusher\.com$/m', $settings['host'], $server);

        return sprintf('pusher://%s:%s@%s?server=%s', $settings['auth_key'], $settings['secret'], $settings['app_id'], $server[1]);
    }

    public function supports(MessageInterface $message): bool
    {
        return $message instanceof PushMessage && (null === $message->getOptions() || $message->getOptions() instanceof PusherOptions);
    }

    protected function doSend(MessageInterface $message): SentMessage
    {
        if (!$message instanceof PushMessage) {
            throw new UnsupportedMessageTypeException(__CLASS__, PushMessage::class, $message);
        }

        if ($message->getOptions() && !$message->getOptions() instanceof PusherOptions) {
            throw new LogicException(sprintf('The "%s" transport only supports instances of "%s" for options.', __CLASS__, PusherOptions::class));
        }

        if (!($options = $message->getOptions()) && $notification = $message->getNotification()) {
            $options = PusherOptions::fromNotification($notification);
        }

        try {
            $response = $this->pusherClient->trigger($options['channel'], $message->getSubject(), json_encode($message->getContent(), JSON_THROW_ON_ERROR));
        } catch (Throwable) {
            throw new RuntimeException('An error occurred at Pusher Notifier Transport');
        }

        $sentMessage = new SentMessage($message, $this->__toString());
        $sentMessage->setMessageId($response['ts']);

        return $sentMessage;
    }
}
