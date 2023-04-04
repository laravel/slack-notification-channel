<?php

namespace Illuminate\Tests\Notifications\Slack\Unit\Blocks;

use Illuminate\Notifications\Slack\BlockKit\Blocks\ContextBlock;
use Illuminate\Tests\Notifications\Slack\TestCase;
use LogicException;

class ContextBlockTest extends TestCase
{
    /** @test */
    public function it_is_arrayable(): void
    {
        $block = new ContextBlock();
        $block->text('Location: 123 Main Street, New York, NY 10010');

        $this->assertSame([
            'type' => 'context',
            'elements' => [
                [
                    'type' => 'plain_text',
                    'text' => 'Location: 123 Main Street, New York, NY 10010',
                ],
            ],
        ], $block->toArray());
    }

    /** @test */
    public function it_requires_at_least_one_element(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('There must be at least one element in each context block.');

        $block = new ContextBlock();
        $block->toArray();
    }

    /** @test */
    public function it_does_not_allow_more_than_ten_elements(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('There is a maximum of 10 elements in each context block.');

        $block = new ContextBlock();
        for ($i = 0; $i < 11; $i++) {
            $block->text('Location: 123 Main Street, New York, NY 10010');
        }

        $block->toArray();
    }

    /** @test */
    public function it_can_manually_specify_the_block_id_field(): void
    {
        $block = new ContextBlock();
        $block->text('Location: 123 Main Street, New York, NY 10010');
        $block->id('actions1');

        $this->assertSame([
            'type' => 'context',
            'elements' => [
                [
                    'type' => 'plain_text',
                    'text' => 'Location: 123 Main Street, New York, NY 10010',
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

        $block = new ContextBlock();
        $block->text('Location: 123 Main Street, New York, NY 10010');
        $block->id(str_repeat('a', 256));

        $block->toArray();
    }

    /** @test */
    public function it_can_add_image_blocks(): void
    {
        $block = new ContextBlock();
        $block->image('https://image.freepik.com/free-photo/red-drawing-pin_1156-445.jpg')->alt('images');
        $block->image('http://placekitten.com/500/500', 'An incredibly cute kitten.');

        $this->assertSame([
            'type' => 'context',
            'elements' => [
                [
                    'type' => 'image',
                    'image_url' => 'https://image.freepik.com/free-photo/red-drawing-pin_1156-445.jpg',
                    'alt_text' => 'images',
                ],
                [
                    'type' => 'image',
                    'image_url' => 'http://placekitten.com/500/500',
                    'alt_text' => 'An incredibly cute kitten.',
                ],
            ],
        ], $block->toArray());
    }

    /** @test */
    public function it_can_add_text_blocks(): void
    {
        $block = new ContextBlock();
        $block->text('Location: 123 Main Street, New York, NY 10010');
        $block->text('Description: **Bring your dog!**')->markdown();

        $this->assertSame([
            'type' => 'context',
            'elements' => [
                [
                    'type' => 'plain_text',
                    'text' => 'Location: 123 Main Street, New York, NY 10010',
                ],
                [
                    'type' => 'mrkdwn',
                    'text' => 'Description: **Bring your dog!**',
                ],
            ],
        ], $block->toArray());
    }
}
