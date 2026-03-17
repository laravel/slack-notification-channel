<?php

namespace Illuminate\Tests\Notifications\Slack\Unit\Blocks;

use Illuminate\Notifications\Slack\BlockKit\Blocks\DividerBlock;
use Illuminate\Tests\Notifications\Slack\TestCase;
use LogicException;

class DividerBlockTest extends TestCase
{
    public function test_it_is_arrayable(): void
    {
        $block = new DividerBlock();

        $this->assertSame([
            'type' => 'divider',
        ], $block->toArray());
    }

    public function test_it_can_manually_specify_the_block_id_field(): void
    {
        $block = new DividerBlock();
        $block->id('divider1');

        $this->assertSame([
            'type' => 'divider',
            'block_id' => 'divider1',
        ], $block->toArray());
    }

    public function test_the_block_id_field_cannot_exceed_255_characters(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Maximum length for the block_id field is 255 characters.');

        $block = new DividerBlock();
        $block->id(str_repeat('a', 256));

        $block->toArray();
    }
}
