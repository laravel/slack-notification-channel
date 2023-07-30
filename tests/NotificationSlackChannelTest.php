<?php

namespace Illuminate\Tests\Notifications;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\SlackWebhookChannel;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Tests\Notifications\Slack\SlackChannelTestNotifiable;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class NotificationSlackChannelTest extends TestCase
{
    /**
     * @var \Illuminate\Notifications\Channels\SlackWebhookChannel
     */
    private $slackChannel;

    /**
     * @var \Mockery\MockInterface|\GuzzleHttp\Client
     */
    private $guzzleHttp;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guzzleHttp = m::mock(Client::class);

        $this->slackChannel = new SlackWebhookChannel($this->guzzleHttp);
    }

    protected function tearDown(): void
    {
        m::close();
    }

    /**
     * @dataProvider payloadDataProvider
     */
    public function testCorrectPayloadIsSentToSlack(Notification $notification, array $payload)
    {
        $this->guzzleHttp->shouldReceive('post')->andReturnUsing(function ($argUrl, $argPayload) use ($payload) {
            $this->assertEquals($argUrl, 'url');
            $this->assertEquals($argPayload, $payload);

            return new Response();
        });

        $this->slackChannel->send(new SlackChannelTestNotifiable('url'), $notification);
    }

    public function testHandlingRateLimitedResponse()
    {
        $this->guzzleHttp->shouldReceive('post')->andReturnUsing(function () {
            throw new ClientException('Too Many Requests', new \GuzzleHttp\Psr7\Request('POST', 'url'), new Response(429, ['Retry-After' => 10]));
        });

        $notification = new NotificationSlackChannelTestNotification;

        $this->slackChannel->send(new SlackChannelTestNotifiable('url'), $notification);

        $this->assertEquals(10, $notification->release);
    }

    public function payloadDataProvider()
    {
        return [
            'payloadWithIcon' => $this->getPayloadWithIcon(),
            'payloadWithImageIcon' => $this->getPayloadWithImageIcon(),
            'payloadWithoutOptionalFields' => $this->getPayloadWithoutOptionalFields(),
            'payloadWithAttachmentFieldBuilder' => $this->getPayloadWithAttachmentFieldBuilder(),
        ];
    }

    private function getPayloadWithIcon()
    {
        return [
            new NotificationSlackChannelTestNotification,
            [
                'json' => [
                    'username' => 'Ghostbot',
                    'icon_emoji' => ':ghost:',
                    'channel' => '#ghost-talk',
                    'text' => 'Content',
                    'attachments' => [
                        [
                            'title' => 'Laravel',
                            'title_link' => 'https://laravel.com',
                            'text' => 'Attachment Content',
                            'fallback' => 'Attachment Fallback',
                            'fields' => [
                                [
                                    'title' => 'Project',
                                    'value' => 'Laravel',
                                    'short' => true,
                                ],
                            ],
                            'mrkdwn_in' => ['text'],
                            'footer' => 'Laravel',
                            'footer_icon' => 'https://laravel.com/fake.png',
                            'author_name' => 'Author',
                            'author_link' => 'https://laravel.com/fake_author',
                            'author_icon' => 'https://laravel.com/fake_author.png',
                            'ts' => 1234567890,
                        ],
                    ],
                ],
            ],
        ];
    }

    private function getPayloadWithImageIcon()
    {
        return [
            new NotificationSlackChannelTestNotificationWithImageIcon,
            [
                'json' => [
                    'username' => 'Ghostbot',
                    'icon_url' => 'http://example.com/image.png',
                    'channel' => '#ghost-talk',
                    'text' => 'Content',
                    'attachments' => [
                        [
                            'title' => 'Laravel',
                            'title_link' => 'https://laravel.com',
                            'text' => 'Attachment Content',
                            'fallback' => 'Attachment Fallback',
                            'fields' => [
                                [
                                    'title' => 'Project',
                                    'value' => 'Laravel',
                                    'short' => true,
                                ],
                            ],
                            'mrkdwn_in' => ['text'],
                            'footer' => 'Laravel',
                            'footer_icon' => 'https://laravel.com/fake.png',
                            'ts' => 1234567890,
                        ],
                    ],
                ],
            ],
        ];
    }

    private function getPayloadWithoutOptionalFields()
    {
        return [
            new NotificationSlackChannelWithoutOptionalFieldsTestNotification,
            [
                'json' => [
                    'text' => 'Content',
                    'attachments' => [
                        [
                            'title' => 'Laravel',
                            'title_link' => 'https://laravel.com',
                            'text' => 'Attachment Content',
                            'fields' => [
                                [
                                    'title' => 'Project',
                                    'value' => 'Laravel',
                                    'short' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function getPayloadWithAttachmentFieldBuilder()
    {
        return [
            new NotificationSlackChannelWithAttachmentFieldBuilderTestNotification,
            [
                'json' => [
                    'text' => 'Content',
                    'attachments' => [
                        [
                            'title' => 'Laravel',
                            'text' => 'Attachment Content',
                            'title_link' => 'https://laravel.com',
                            'callback_id' => 'attachment_callbackid',
                            'fields' => [
                                [
                                    'title' => 'Project',
                                    'value' => 'Laravel',
                                    'short' => true,
                                ],
                                [
                                    'title' => 'Special powers',
                                    'value' => 'Zonda',
                                    'short' => false,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}

class NotificationSlackChannelTestNotification extends Notification implements ShouldQueue
{
    public ?int $release = null;

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->from('Ghostbot', ':ghost:')
            ->to('#ghost-talk')
            ->content('Content')
            ->attachment(function ($attachment) {
                $timestamp = m::mock(Carbon::class);
                $timestamp->shouldReceive('getTimestamp')->andReturn(1234567890);
                $attachment->title('Laravel', 'https://laravel.com')
                    ->content('Attachment Content')
                    ->fallback('Attachment Fallback')
                    ->fields([
                        'Project' => 'Laravel',
                    ])
                    ->footer('Laravel')
                    ->footerIcon('https://laravel.com/fake.png')
                    ->markdown(['text'])
                    ->author('Author', 'https://laravel.com/fake_author', 'https://laravel.com/fake_author.png')
                    ->timestamp($timestamp);
            });
    }

    public function release(int $delay = 0)
    {
        $this->release = $delay;
    }
}

class NotificationSlackChannelTestNotificationWithImageIcon extends Notification
{
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->from('Ghostbot')
            ->image('http://example.com/image.png')
            ->to('#ghost-talk')
            ->content('Content')
            ->attachment(function ($attachment) {
                $timestamp = m::mock(Carbon::class);
                $timestamp->shouldReceive('getTimestamp')->andReturn(1234567890);
                $attachment->title('Laravel', 'https://laravel.com')
                    ->content('Attachment Content')
                    ->fallback('Attachment Fallback')
                    ->fields([
                        'Project' => 'Laravel',
                    ])
                    ->footer('Laravel')
                    ->footerIcon('https://laravel.com/fake.png')
                    ->markdown(['text'])
                    ->timestamp($timestamp);
            });
    }
}

class NotificationSlackChannelWithoutOptionalFieldsTestNotification extends Notification
{
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content('Content')
            ->attachment(function ($attachment) {
                $attachment->title('Laravel', 'https://laravel.com')
                    ->content('Attachment Content')
                    ->fields([
                        'Project' => 'Laravel',
                    ]);
            });
    }
}

class NotificationSlackChannelWithAttachmentFieldBuilderTestNotification extends Notification
{
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content('Content')
            ->attachment(function ($attachment) {
                $attachment->title('Laravel', 'https://laravel.com')
                    ->content('Attachment Content')
                    ->field('Project', 'Laravel')
                    ->callbackId('attachment_callbackid')
                    ->field(function ($attachmentField) {
                        $attachmentField
                            ->title('Special powers')
                            ->content('Zonda')
                            ->long();
                    });
            });
    }
}
