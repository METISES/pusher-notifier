<?php

namespace Symfony\Component\Notifier\Bridge\Pusher;

use Symfony\Component\Notifier\Message\MessageOptionsInterface;
use Symfony\Component\Notifier\Notification\Notification;

/**
 * @author Yasmany Cubela Medina <yasmanycm@gmail.com>
 */
final class PusherOptions implements MessageOptionsInterface
{
    private $options;

    public function __construct(array $options = ['async' => false])
    {
        $this->options = $options;
    }

    public static function fromNotification(Notification $notification): self
    {
        return new self();
    }

    public function toArray(): array
    {
        $options = $this->options;
        unset($options['recipient_id']);

        return $options;
    }

    public function getRecipientId(): ?string
    {
        return $this->options['recipient_id'] ?? null;
    }
}
