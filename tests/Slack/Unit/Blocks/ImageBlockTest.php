<?php

namespace Illuminate\Tests\Notifications\Slack\Unit\Blocks;

use Illuminate\Notifications\Slack\BlockKit\Blocks\ImageBlock;
use Illuminate\Tests\Notifications\Slack\TestCase;
use LogicException;

class ImageBlockTest extends TestCase
{
    /** @test */
    public function it_is_arrayable(): void
    {
        $block = new ImageBlock('http://placekitten.com/500/500', 'An incredibly cute kitten.');

        $this->assertSame([
            'type' => 'image',
            'image_url' => 'http://placekitten.com/500/500',
            'alt_text' => 'An incredibly cute kitten.',
        ], $block->toArray());
    }

    /** @test */
    public function the_url_cannot_exceed_3000_characters(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Maximum length for the url field is 3000 characters.');

        new ImageBlock(str_repeat('a', 3001));
    }

    /** @test */
    public function the_alt_text_is_required(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Alt text is required for an image block.');

        $block = new ImageBlock('http://placekitten.com/500/500');

        $block->toArray();
    }

    /** @test */
    public function the_alt_text_cannot_exceed_2000_characters(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Maximum length for the alt text field is 2000 characters.');

        $block = new ImageBlock('http://placekitten.com/500/500');
        $block->alt(str_repeat('a', 2001));

        $block->toArray();
    }

    /** @test */
    public function it_can_have_a_title(): void
    {
        $block = new ImageBlock('http://placekitten.com/500/500', 'An incredibly cute kitten.');
        $block->title('This one is a cutesy kitten in a box.');

        $this->assertSame([
            'type' => 'image',
            'image_url' => 'http://placekitten.com/500/500',
            'alt_text' => 'An incredibly cute kitten.',
            'title' => [
                'type' => 'plain_text',
                'text' => 'This one is a cutesy kitten in a box.',
            ],
        ], $block->toArray());
    }

    /** @test */
    public function the_title_field_cannot_exceed_2000_characters(): void
    {
        $block = new ImageBlock('http://placekitten.com/500/500', 'An incredibly cute kitten.');
        $block->title(str_repeat('a', 2001));

        $this->assertSame([
            'type' => 'image',
            'image_url' => 'http://placekitten.com/500/500',
            'alt_text' => 'An incredibly cute kitten.',
            'title' => [
                'type' => 'plain_text',
                'text' => str_repeat('a', 1997).'...',
            ],
        ], $block->toArray());
    }

    /** @test */
    public function it_can_manually_specify_the_block_id_field(): void
    {
        $block = new ImageBlock('http://placekitten.com/500/500');
        $block->alt('An incredibly cute kitten.');
        $block->id('actions1');

        $this->assertSame([
            'type' => 'image',
            'image_url' => 'http://placekitten.com/500/500',
            'alt_text' => 'An incredibly cute kitten.',
            'block_id' => 'actions1',
        ], $block->toArray());
    }

    /** @test */
    public function the_block_id_field_cannot_exceed_255_characters(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Maximum length for the block_id field is 255 characters.');

        $block = new ImageBlock('http://placekitten.com/500/500');
        $block->id(str_repeat('a', 256));

        $block->toArray();
    }
}
