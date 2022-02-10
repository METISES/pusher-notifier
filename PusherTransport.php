<?php

namespace Symfony\Component\Notifier\Bridge\Pusher;

use Pusher\Pusher;
use Symfony\Component\Notifier\Exception\InvalidArgumentException;
use Symfony\Component\Notifier\Exception\LogicException;
use Symfony\Component\Notifier\Exception\TransportException;
use Symfony\Component\Notifier\Exception\UnsupportedMessageTypeException;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\PushMessage;
use Symfony\Component\Notifier\Message\SentMessage;
use Symfony\Component\Notifier\Transport\AbstractTransport;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @author Yasmany Cubela Medina <yasmanycm@gmail.com>
 */
final class PusherTransport extends AbstractTransport
{
    private $pusherClient;

    public function __construct(Pusher $pusherClient, HttpClientInterface $client = null, EventDispatcherInterface $dispatcher = null)
    {
        $this->pusherClient = $pusherClient;

        parent::__construct($client, $dispatcher);
    }

    public function __toString(): string
    {
        return sprintf('pusher://%s', $this->getEndpoint());
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

        if (!($opts = $message->getOptions()) && $notification = $message->getNotification()) {
            $opts = PusherOptions::fromNotification($notification);
        }

        $options = $opts ? $opts->toArray() : [];
        if (!isset($options['channel'])) {
            $options['channel'] = $message->getRecipientId() ?: $this->chatChannel;
        }
        $options['text'] = $message->getSubject();
        $response = $this->client->request('POST', 'https://'.$this->getEndpoint().'/api/chat.postMessage', [
            'json' => array_filter($options),
            'auth_bearer' => $this->accessToken,
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8',
            ],
        ]);

        try {
            $statusCode = $response->getStatusCode();
        } catch (TransportExceptionInterface $e) {
            throw new TransportException('Could not reach the remote Pusher server.', $response, 0, $e);
        }

        if (200 !== $statusCode) {
            throw new TransportException(sprintf('Unable to post the Pusher message: "%s".', $response->getContent(false)), $response);
        }

        $result = $response->toArray(false);
        if (!$result['ok']) {
            $errors = isset($result['errors']) ? ' ('.implode('|', $result['errors']).')' : '';

            throw new TransportException(sprintf('Unable to post the Pusher message: "%s"%s.', $result['error'], $errors), $response);
        }

        $sentMessage = new SentMessage($message, (string) $this);
        $sentMessage->setMessageId($result['ts']);

        return $sentMessage;
    }
}
