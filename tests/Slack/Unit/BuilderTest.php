<?php

namespace Illuminate\Tests\Notifications\Slack\Unit;

use Illuminate\Notifications\Slack\BlockKit\Builder;
use Illuminate\Tests\Notifications\Slack\TestCase;
use JsonException;

class BuilderTest extends TestCase
{
    /** @test */
    public function it_is_arrayable_and_removes_the_blocks_key(): void
    {
        $builder = new Builder(<<<'JSON'
            {
                "blocks": [
                    {
                        "type": "section",
                        "text": {
                            "type": "plain_text",
                            "text": "This is a plain text section block.",
                            "emoji": true
                        }
                    },
                    {
                        "type": "actions",
                        "elements": [
                            {
                                "type": "button",
                                "text": {
                                    "type": "plain_text",
                                    "text": "Click Me",
                                    "emoji": true
                                },
                                "value": "click_me_123",
                                "action_id": "actionId-0"
                            }
                        ]
                    }
                ]
            }
        JSON);

        $this->assertSame([
            [
                'type'=> 'section',
                'text'=> [
                    'type'=> 'plain_text',
                    'text'=> 'This is a plain text section block.',
                    'emoji'=> true
                ],
            ],
            [
                'type' => 'actions',
                'elements' => [
                    [
                        'type' => 'button',
                        'text' => [
                            'type' => 'plain_text',
                            'text' => 'Click Me',
                            'emoji' => true,
                        ],
                        'value' => 'click_me_123',
                        'action_id' => 'actionId-0',
                    ],
                ],
            ],
        ], $builder->toArray());
    }

    /** @test */
    public function it_throws_an_exception_if_payload_is_invalid_json(): void
    {
        $this->expectException(JsonException::class);

        $builder = new Builder('!!!');
        $builder->toArray();
    }
}
