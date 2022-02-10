<?php

declare(strict_types=1);

namespace Symfony\Component\Notifier\Bridge\Pusher\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Notifier\Bridge\Pusher\PusherOptions;
use Symfony\Component\Notifier\Notification\Notification;

/**
 * @author Yasmany Cubela Medina <yasmanycm@gmail.com>
 *
 * @internal
 * @coversNothing
 */
final class PusherOptionsTest extends TestCase
{
    /**
     * @dataProvider toArrayProvider
     * @dataProvider toArraySimpleOptionsProvider
     */
    public function testToArray(array $options, array $expected = null): void
    {
        static::assertSame($expected ?? $options, (new PusherOptions($options))->toArray());
    }

    public function toArrayProvider(): iterable
    {
        yield 'empty is allowed' => [
            [],
            [],
        ];

        yield 'always unset recipient_id' => [
            ['recipient_id' => '42'],
            [],
        ];
    }

    public function toArraySimpleOptionsProvider(): iterable
    {
        yield [['async' => true]];
    }

    /**
     * @dataProvider getRecipientIdProvider
     */
    public function testGetRecipientId(?string $expected, PusherOptions $options): void
    {
        static::assertSame($expected, $options->getRecipientId());
    }

    public function getRecipientIdProvider(): iterable
    {
        yield [null, new PusherOptions()];
        yield [null, (new PusherOptions(['recipient_id' => null]))];
        yield ['foo', (new PusherOptions(['recipient_id' => 'foo']))];
    }

    public function setProvider(): iterable
    {
        yield ['async', 'async', true];
    }

    /**
     * @dataProvider fromNotificationProvider
     */
    public function testFromNotification(array $expected, Notification $notification): void
    {
        $options = PusherOptions::fromNotification($notification);

        static::assertSame($expected, $options->toArray());
    }

    public function fromNotificationProvider(): iterable
    {
        $subject = 'Hi!';

        yield 'without content + without exception' => [
            [
                'async' => false,
            ],
            new Notification($subject),
        ];
    }
}
