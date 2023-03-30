<?php

namespace Illuminate\Tests\Notifications\Slack\Unit\Blocks;

use Illuminate\Notifications\Slack\BlockKit\Blocks\ActionsBlock;
use Illuminate\Tests\Notifications\Slack\TestCase;
use LogicException;

class ActionsBlockTest extends TestCase
{
    /** @test */
    public function it_is_arrayable(): void
    {
        $block = new ActionsBlock();
        $block->button('Example Button');

        $this->assertSame([
            'type' => 'actions',
            'elements' => [
                [
                    'type' => 'button',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Example Button',
                    ],
                    'action_id' => 'button_example-button',
                ],
            ],
        ], $block->toArray());
    }

    /** @test */
    public function it_requires_at_least_one_element(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('There must be at least one element in each actions block.');

        $block = new ActionsBlock();
        $block->toArray();
    }

    /** @test */
    public function it_does_not_allow_more_than_twenty_five_elements(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('There is a maximum of 25 elements in each actions block.');

        $block = new ActionsBlock();
        for ($i = 0; $i < 26; $i++) {
            $block->button('Button');
        }

        $block->toArray();
    }

    /** @test */
    public function it_can_manually_specify_the_block_id_field(): void
    {
        $block = new ActionsBlock();
        $block->button('Example Button');
        $block->id('actions1');

        $this->assertSame([
            'type' => 'actions',
            'elements' => [
                [
                    'type' => 'button',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Example Button',
                    ],
                    'action_id' => 'button_example-button',
                ],
            ],
            'block_id' => 'actions1',
        ], $block->toArray());
    }

    /** @test */
    public function the_block_id_field_cannot_exceed_255_characters(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Maximum length for the block_id field is 255 characters.');

        $block = new ActionsBlock();
        $block->button('Button');
        $block->id(str_repeat('a', 256));

        $block->toArray();
    }

    /** @test */
    public function it_can_add_buttons(): void
    {
        $block = new ActionsBlock();
        $block->button('Example Button');
        $block->button('Scary Button')->danger();

        $this->assertSame([
            'type' => 'actions',
            'elements' => [
                [
                    'type' => 'button',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Example Button',
                    ],
                    'action_id' => 'button_example-button',
                ],
                [
                    'type' => 'button',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Scary Button',
                    ],
                    'action_id' => 'button_scary-button',
                    'style' => 'danger',
                ],
            ],
        ], $block->toArray());
    }
}
