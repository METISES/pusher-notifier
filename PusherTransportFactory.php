<?php

namespace Symfony\Component\Notifier\Bridge\Pusher;

use Pusher\Pusher;
use Symfony\Component\Notifier\Exception\IncompleteDsnException;
use Symfony\Component\Notifier\Exception\UnsupportedSchemeException;
use Symfony\Component\Notifier\Transport\AbstractTransportFactory;
use Symfony\Component\Notifier\Transport\Dsn;
use Symfony\Component\Notifier\Transport\TransportInterface;

/**
 * @author Yasmany Cubela Medina <yasmanycm@gmail.com>
 */
final class PusherTransportFactory extends AbstractTransportFactory
{
    /**
     * @return PusherTransport
     */
    public function create(Dsn $dsn): TransportInterface
    {
        if ('pusher' !== $dsn->getScheme()) {
            throw new UnsupportedSchemeException($dsn, 'pusher', $this->getSupportedSchemes());
        }

        $options = [
            'cluster' => $cluster,
            'useTLS' => true
        ];

        $pusherClient = new Pusher($appKey, $appSecret, $appId, $options);

        return new PusherTransport($pusherClient, $this->dispatcher);
    }

    protected function getSupportedSchemes(): array
    {
        return ['pusher'];
    }
}
