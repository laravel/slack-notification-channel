<?php

namespace Illuminate\Tests\Notifications\Slack\Feature;

use Closure;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Request;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ActionsBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ContextBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ImageBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\SlackChannel;
use Illuminate\Notifications\Slack\SlackMessage;
use Illuminate\Notifications\Slack\SlackRoute;
use Illuminate\Support\Facades\Http;
use Illuminate\Tests\Notifications\Slack\SlackChannelTestNotifiable;
use Illuminate\Tests\Notifications\Slack\SlackChannelTestNotification;
use Illuminate\Tests\Notifications\Slack\TestCase;
use LogicException;
use RuntimeException;

class SlackMessageTest extends TestCase
{
    protected function sendNotification(Closure $callback, $routeChannel = '#ghost-talk'): self
    {
        $this->slackChannel->send(
            new SlackChannelTestNotifiable(new SlackRoute($routeChannel, 'fake-token')),
            new SlackChannelTestNotification($callback)
        );

        return $this;
    }

    protected function assertNotificationSent(array $payload): void
    {
        Http::assertSent(function (Request $request) use ($payload) {
            $this->assertEquals($request->url(), 'https://slack.com/api/chat.postMessage');
            $this->assertEquals($request->header('Authorization')[0], 'Bearer fake-token');
            $this->assertEquals($request->header('Content-Type')[0], 'application/json');
            $this->assertSame(json_decode($request->body(), true), $payload);

            return true;
        });
    }

    /** @test */
    public function it_throws_an_exception_when_no_text_or_block_was_provided(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Slack messages must contain at least a text message or block.');

        $this->sendNotification(function (SlackMessage $message) {
            $message->to('foo');
        });
    }

    /** @test */
    public function it_throws_an_exception_when_too_many_blocks_are_defined(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Slack messages can only contain up to 50 blocks.');

        $this->sendNotification(function (SlackMessage $message) {
            for ($i = 0; $i < 51; $i++) {
                $message->dividerBlock();
            }
        });
    }

    /** @test */
    public function it_sends_a_very_basic_message(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->text('This is a simple Web API text message. See https://api.slack.com/reference/messaging/payload for more information.');
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'text' => 'This is a simple Web API text message. See https://api.slack.com/reference/messaging/payload for more information.',
        ]);
    }

    /** @test */
    public function it_fails_to_send_a_message_when_the_slack_app_is_not_configured_correctly(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Slack API call failed with error [invalid_auth].');

        $channel = new SlackChannel(new Factory());

        $channel->send(
            new SlackChannelTestNotifiable(new SlackRoute('#general', 'invalid-token')),
            new SlackChannelTestNotification(function (SlackMessage $message) {
                $message->text('This is a simple Web API text message. See https://api.slack.com/reference/messaging/payload for more information.');
            })
        );
    }

    /** @test */
    public function it_can_set_the_default_channel_for_the_message(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->text('See https://api.slack.com/methods/chat.postMessage for more information.');
            $message->to('#general');
        }, null)->assertNotificationSent([
            'channel' => '#general',
            'text' => 'See https://api.slack.com/methods/chat.postMessage for more information.',
        ]);
    }

    /** @test */
    public function it_can_use_an_emoji_as_the_icon_for_the_message(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->text('See https://api.slack.com/methods/chat.postMessage for more information.');
            $message->image('emoji-overrides-image-url-automatically-according-to-spec')->emoji(':ghost:');
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'text' => 'See https://api.slack.com/methods/chat.postMessage for more information.',
            'icon_emoji' => ':ghost:',
        ]);
    }

    /** @test */
    public function it_can_use_an_image_as_the_icon_for_the_message(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->text('See https://api.slack.com/methods/chat.postMessage for more information.');
            $message->emoji('auto-clearing-as-to-prefer-image-since-its-called-after')->image('http://lorempixel.com/48/48');
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'text' => 'See https://api.slack.com/methods/chat.postMessage for more information.',
            'icon_url' => 'http://lorempixel.com/48/48',
        ]);
    }

    /** @test */
    public function it_can_include_metadata(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->text('See https://api.slack.com/methods/chat.postMessage for more information.');
            $message->metadata('task_created', ['id' => '11223', 'title' => 'Redesign Homepage']);
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'text' => 'See https://api.slack.com/methods/chat.postMessage for more information.',
            'metadata' => [
                'event_type' => 'task_created',
                'event_payload' => ['id' => '11223', 'title' => 'Redesign Homepage'],
            ],
        ]);
    }

    /** @test */
    public function it_can_disable_slack_markdown_parsing(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->text('See https://api.slack.com/methods/chat.postMessage for more information.');
            $message->disableMarkdownParsing();
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'text' => 'See https://api.slack.com/methods/chat.postMessage for more information.',
            'mrkdwn' => false,
        ]);
    }

    /** @test */
    public function it_can_unfurl_links(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->text('See https://api.slack.com/methods/chat.postMessage for more information.');
            $message->unfurlLinks();
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'text' => 'See https://api.slack.com/methods/chat.postMessage for more information.',
            'unfurl_links' => true,
        ]);
    }

    /** @test */
    public function it_can_unfurl_media(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->text('See https://api.slack.com/methods/chat.postMessage for more information.');
            $message->unfurlMedia();
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'text' => 'See https://api.slack.com/methods/chat.postMessage for more information.',
            'unfurl_media' => true,
        ]);
    }

    /** @test */
    public function it_can_reply_as_thread(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->text('See https://api.slack.com/methods/chat.postMessage for more information.');
            $message->threadTimestamp('123456.7890');
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'text' => 'See https://api.slack.com/methods/chat.postMessage for more information.',
            'thread_ts' => '123456.7890',
        ]);
    }

    /** @test */
    public function it_can_send_threaded_reply_as_broadcast_reference(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->text('See https://api.slack.com/methods/chat.postMessage for more information.');
            $message->broadcastReply(true);
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'text' => 'See https://api.slack.com/methods/chat.postMessage for more information.',
            'reply_broadcast' => true,
        ]);
    }

    /** @test */
    public function it_can_set_the_bot_user_name(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->text('See https://api.slack.com/methods/chat.postMessage for more information.');
            $message->username('larabot');
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'text' => 'See https://api.slack.com/methods/chat.postMessage for more information.',
            'username' => 'larabot',
        ]);
    }

    /** @test */
    public function it_contains_both_blocks_and_a_fallback_text_used_in_notifications_only(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->text('This is now a fallback text used in notifications. See https://api.slack.com/methods/chat.postMessage for more information.');
            $message->dividerBlock();
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'text' => 'This is now a fallback text used in notifications. See https://api.slack.com/methods/chat.postMessage for more information.',
            'blocks' => [
                [
                    'type' => 'divider',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_can_contain_action_blocks(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->actionsBlock(function (ActionsBlock $actions) {
                $actions->button('Cancel')->value('cancel')->id('button_1');
            });
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'blocks' => [
                [
                    'type' => 'actions',
                    'elements' => [
                        [
                            'type' => 'button',
                            'text' => [
                                'type' => 'plain_text',
                                'text' => 'Cancel',
                            ],
                            'action_id' => 'button_1',
                            'value' => 'cancel',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function it_can_contain_context_blocks(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->contextBlock(function (ContextBlock $context) {
                $context->image('https://image.freepik.com/free-photo/red-drawing-pin_1156-445.jpg')->alt('images');
            });
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'blocks' => [
                [
                    'type' => 'context',
                    'elements' => [
                        [
                            'type' => 'image',
                            'image_url' => 'https://image.freepik.com/free-photo/red-drawing-pin_1156-445.jpg',
                            'alt_text' => 'images',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function it_can_contain_divider_blocks(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->dividerBlock();
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'blocks' => [
                [
                    'type' => 'divider',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_can_contain_header_blocks(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->headerBlock('Budget Performance');
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'blocks' => [
                [
                    'type' => 'header',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Budget Performance',
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function it_can_contain_image_blocks(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->imageBlock('http://placekitten.com/500/500', function (ImageBlock $imageBlock) {
                $imageBlock->alt('An incredibly cute kitten.');
            });
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'blocks' => [
                [
                    'type' => 'image',
                    'image_url' => 'http://placekitten.com/500/500',
                    'alt_text' => 'An incredibly cute kitten.',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_can_contain_section_blocks(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->sectionBlock(function (SectionBlock $sectionBlock) {
                $sectionBlock->text('A message *with some bold text* and _some italicized text_.')->markdown();
            });
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'blocks' => [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => 'A message *with some bold text* and _some italicized text_.',
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function it_can_add_blocks_conditionally(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->when(true, function (SlackMessage $message) {
                $message->sectionBlock(function (SectionBlock $sectionBlock) {
                    $sectionBlock->text('I *will* be included.')->markdown();
                });
            })->when(false, function (SlackMessage $message) {
                $message->sectionBlock(function (SectionBlock $sectionBlock) {
                    $sectionBlock->text("I *won't* be included.")->markdown();
                });
            })->when(false, function (SlackMessage $message) {
                $message->sectionBlock(function (SectionBlock $sectionBlock) {
                    $sectionBlock->text("I'm *not* included either...")->markdown();
                });
            }, function (SlackMessage $message) {
                $message->sectionBlock(function (SectionBlock $sectionBlock) {
                    $sectionBlock->text('But I *will* be included!')->markdown();
                });
            });
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'blocks' => [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => 'I *will* be included.',
                    ],
                ],
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => 'But I *will* be included!',
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function it_submits_blocks_in_the_order_they_were_defined(): void
    {
        $this->sendNotification(function (SlackMessage $message) {
            $message->headerBlock('Budget Performance');
            $message->sectionBlock(function (SectionBlock $sectionBlock) {
                $sectionBlock->text('A message *with some bold text* and _some italicized text_.')->markdown();
            });
            $message->headerBlock('Market Performance');
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'blocks' => [
                [
                    'type' => 'header',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Budget Performance',
                    ],
                ],
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => 'A message *with some bold text* and _some italicized text_.',
                    ],
                ],
                [
                    'type' => 'header',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Market Performance',
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function it_can_use_copied_block_kit_builder_json(){
        $this->sendNotification(function (SlackMessage $message) {
            $message->blockBuilder(<<<'JSON'
                {
                    "blocks": [
                        {
                            "type": "header",
                            "text": {
                                "type": "plain_text",
                                "text": "This is a header block",
                                "emoji": true
                            }
                        },
                        {
                            "type": "context",
                            "elements": [
                                {
                                    "type": "image",
                                    "image_url": "https://pbs.twimg.com/profile_images/625633822235693056/lNGUneLX_400x400.jpg",
                                    "alt_text": "cute cat"
                                },
                                {
                                    "type": "mrkdwn",
                                    "text": "*Cat* has approved this message."
                                }
                            ]
                        },
                        {
                            "type": "image",
                            "image_url": "https://assets3.thrillist.com/v1/image/1682388/size/tl-horizontal_main.jpg",
                            "alt_text": "delicious tacos"
                        }
                    ]
                }
            JSON);
        })->assertNotificationSent([
            'channel' => '#ghost-talk',
            'blocks' => [
                [
                    'type' => 'header',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'This is a header block',
                        'emoji' => true,
                    ],
                ],
                [
                    'type' => 'context',
                    'elements' => [
                        [
                            'type' => 'image',
                            'image_url' => 'https://pbs.twimg.com/profile_images/625633822235693056/lNGUneLX_400x400.jpg',
                            'alt_text' => 'cute cat',
                        ],
                        [
                            'type' => 'mrkdwn',
                            'text' => '*Cat* has approved this message.',
                        ],
                    ],
                ],
                [
                    'type' => 'image',
                    'image_url' => 'https://assets3.thrillist.com/v1/image/1682388/size/tl-horizontal_main.jpg',
                    'alt_text' => 'delicious tacos',
                ],
            ],
        ]);
    }
}
