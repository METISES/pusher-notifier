<?php

namespace Symfony\Component\Notifier\Bridge\Pusher\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ExpectDeprecationTrait;
use Symfony\Component\Notifier\Bridge\Pusher\PusherOptions;
use Symfony\Component\Notifier\Exception\LogicException;
use Symfony\Component\Notifier\Notification\Notification;

/**
 * @author Yasmany Cubela Medina <yasmanycm@gmail.com>
 */
final class PusherOptionsTest extends TestCase
{
    use ExpectDeprecationTrait;

    /**
     * @dataProvider toArrayProvider
     * @dataProvider toArraySimpleOptionsProvider
     */
    public function testToArray(array $options, array $expected = null)
    {
        $this->assertSame($expected ?? $options, (new PusherOptions($options))->toArray());
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

        yield 'blocks containing 1 divider block' => [
            [
                'blocks' => [
                    $block = new SlackDividerBlock(),
                ],
            ],
            [
                'blocks' => [
                    $block,
                ],
            ],
        ];
    }

    public function toArraySimpleOptionsProvider(): iterable
    {
        yield [['as_user' => true]];
        yield [['icon_emoji' => 'foo']];
        yield [['icon_url' => 'https://symfony.com']];
        yield [['link_names' => true]];
        yield [['mrkdwn' => true]];
        yield [['parse' => 'bar']];
        yield [['unfurl_links' => true]];
        yield [['unfurl_media' => true]];
        yield [['username' => 'baz']];
        yield [['thread_ts' => '1503435956.000247']];
    }

    /**
     * @dataProvider getRecipientIdProvider
     */
    public function testGetRecipientId(?string $expected, PusherOptions $options)
    {
        $this->assertSame($expected, $options->getRecipientId());
    }

    public function getRecipientIdProvider(): iterable
    {
        yield [null, new PusherOptions()];
        yield [null, (new PusherOptions(['recipient_id' => null]))];
        yield ['foo', (new PusherOptions())->recipient('foo')];
        yield ['foo', (new PusherOptions(['recipient_id' => 'foo']))];
    }

    /**
     * @dataProvider setProvider
     *
     * @param mixed $value
     */
    public function testSet(string $method, string $optionsKey, $value)
    {
        $options = (new PusherOptions())->$method($value);

        $this->assertSame($value, $options->toArray()[$optionsKey]);
    }

    public function setProvider(): iterable
    {
        yield ['asUser', 'as_user', true];
        yield ['iconEmoji', 'icon_emoji', 'foo'];
        yield ['iconUrl', 'icon_url', 'https://symfony.com'];
        yield ['linkNames', 'link_names', true];
        yield ['mrkdwn', 'mrkdwn', true];
        yield ['parse', 'parse', 'bar'];
        yield ['unfurlLinks', 'unfurl_links', true];
        yield ['unfurlMedia', 'unfurl_media', true];
        yield ['username', 'username', 'baz'];
        yield ['threadTs', 'thread_ts', '1503435956.000247'];
    }

    public function testSetBlock()
    {
        $options = (new PusherOptions())->block(new SlackDividerBlock());

        $this->assertSame([['type' => 'divider']], $options->toArray()['blocks']);
    }

    /**
     * @group legacy
     */
    public function testChannelMethodRaisesDeprecation()
    {
        $this->expectDeprecation('Since symfony/pusher-notifier 5.1: The "Symfony\Component\Notifier\Bridge\Pusher\SlackOptions::channel()" method is deprecated, use "recipient()" instead.');

        (new PusherOptions())->channel('channel');
    }

    /**
     * @dataProvider fromNotificationProvider
     */
    public function testFromNotification(array $expected, Notification $notification)
    {
        $options = PusherOptions::fromNotification($notification);

        $this->assertSame($expected, $options->toArray());
    }

    public function fromNotificationProvider(): iterable
    {
        $subject = 'Hi!';
        $emoji = '🌧️';
        $content = 'Content here ...';

        yield 'without content + without exception' => [
            [
                'icon_emoji' => $emoji,
                'blocks' => [
                    [
                        'type' => 'section',
                        'text' => [
                            'type' => 'mrkdwn',
                            'text' => $subject,
                        ],
                    ],
                ],
            ],
            (new Notification($subject))->emoji($emoji),
        ];

        yield 'with content + without exception' => [
            [
                'icon_emoji' => $emoji,
                'blocks' => [
                    [
                        'type' => 'section',
                        'text' => [
                            'type' => 'mrkdwn',
                            'text' => $subject,
                        ],
                    ],
                    [
                        'type' => 'section',
                        'text' => [
                            'type' => 'mrkdwn',
                            'text' => $content,
                        ],
                    ],
                ],
            ],
            (new Notification($subject))->emoji($emoji)->content($content),
        ];
    }

    public function testConstructWithMaximumBlocks()
    {
        $options = new PusherOptions(['blocks' => array_map(static function () { return ['type' => 'divider']; }, range(0, 49))]);

        $this->assertCount(50, $options->toArray()['blocks']);
    }

    public function testConstructThrowsWithTooManyBlocks()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Maximum number of "blocks" has been reached (50).');

        new PusherOptions(['blocks' => array_map(static function () { return ['type' => 'divider']; }, range(0, 50))]);
    }

    public function testAddMaximumBlocks()
    {
        $options = new PusherOptions();
        for ($i = 0; $i < 50; ++$i) {
            $options->block(new SlackSectionBlock());
        }

        $this->assertCount(50, $options->toArray()['blocks']);
    }

    public function testThrowsWhenBlocksLimitReached()
    {
        $options = new PusherOptions();
        for ($i = 0; $i < 50; ++$i) {
            $options->block(new SlackSectionBlock());
        }

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Maximum number of "blocks" has been reached (50).');

        $options->block(new SlackSectionBlock());
    }
}
