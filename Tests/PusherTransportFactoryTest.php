<?php

namespace Symfony\Component\Notifier\Bridge\Pusher\Tests;

use Pusher\Pusher;
use Symfony\Component\Notifier\Bridge\Pusher\PusherTransportFactory;
use Symfony\Component\Notifier\Exception\InvalidArgumentException;
use Symfony\Component\Notifier\Test\TransportFactoryTestCase;
use Symfony\Component\Notifier\Transport\Dsn;
use Symfony\Component\Notifier\Transport\TransportFactoryInterface;

/**
 * @author Yasmany Cubela Medina <yasmanycm@gmail.com>
 */
final class PusherTransportFactoryTest extends TransportFactoryTestCase
{
    /**
     * @return PusherTransportFactory
     */
    public function createFactory(): TransportFactoryInterface
    {
        return new PusherTransportFactory();
    }

    public function createProvider(): iterable
    {
        yield [
            'pusher://host.test',
            'pusher://xoxb-TestToken@host.test',
        ];

        yield 'with path' => [
            'pusher://host.test?channel=testChannel',
            'pusher://xoxb-TestToken@host.test/?channel=testChannel',
        ];

        yield 'without path' => [
            'pusher://host.test?channel=testChannel',
            'pusher://xoxb-TestToken@host.test?channel=testChannel',
        ];
    }

    public function supportsProvider(): iterable
    {
        yield [true, 'pusher://xoxb-TestToken@host?channel=testChannel'];
        yield [false, 'somethingElse://xoxb-TestToken@host?channel=testChannel'];
    }

    public function incompleteDsnProvider(): iterable
    {
        yield 'missing token' => ['pusher://host.test?channel=testChannel'];
    }

    public function unsupportedSchemeProvider(): iterable
    {
        yield ['somethingElse://xoxb-TestToken@host?channel=testChannel'];
    }
}
