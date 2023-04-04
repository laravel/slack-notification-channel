<?php

namespace Illuminate\Tests\Notifications\Slack\Unit\Blocks;

use Illuminate\Notifications\Slack\BlockKit\Blocks\HeaderBlock;
use Illuminate\Tests\Notifications\Slack\TestCase;
use LogicException;

class HeaderBlockTest extends TestCase
{
    /** @test */
    public function it_is_arrayable(): void
    {
        $block = new HeaderBlock('Budget Performance');

        $this->assertSame([
            'type' => 'header',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Budget Performance',
            ],
        ], $block->toArray());
    }

    /** @test */
    public function the_text_heading_cannot_exceed_150_characters(): void
    {
        $blockA = new HeaderBlock(str_repeat('a', 151));
        $blockB = new HeaderBlock(str_repeat('b', 150));

        $this->assertSame([
            'type' => 'header',
            'text' => [
                'type' => 'plain_text',
                'text' => str_repeat('a', 147).'...',
            ],
        ], $blockA->toArray());

        $this->assertSame([
            'type' => 'header',
            'text' => [
                'type' => 'plain_text',
                'text' => str_repeat('b', 150),
            ],
        ], $blockB->toArray());
    }

    /** @test */
    public function it_can_manually_specify_the_block_id_field(): void
    {
        $block = new HeaderBlock('Budget Performance');
        $block->id('header1');

        $this->assertSame([
            'type' => 'header',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Budget Performance',
            ],
            'block_id' => 'header1',
        ], $block->toArray());
    }

    /** @test */
    public function the_block_id_field_cannot_exceed_255_characters(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Maximum length for the block_id field is 255 characters.');

        $block = new HeaderBlock('Budget Performance');
        $block->id(str_repeat('a', 256));

        $block->toArray();
    }
}
