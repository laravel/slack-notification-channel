<?php

namespace Illuminate\Tests\Notifications\Slack\Unit\Blocks;

use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\BlockKit\Elements\ImageElement;
use Illuminate\Tests\Notifications\Slack\TestCase;
use LogicException;

class SectionBlockTest extends TestCase
{
    /** @test */
    public function it_is_arrayable(): void
    {
        $block = new SectionBlock();
        $block->text('Location: 123 Main Street, New York, NY 10010');

        $this->assertSame([
            'type' => 'section',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Location: 123 Main Street, New York, NY 10010',
            ],
        ], $block->toArray());
    }

    /** @test */
    public function it_throws_an_exception_when_no_text_or_field_was_provided(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('A section requires at least one block, or the text to be set.');

        $block = new SectionBlock();

        $block->toArray();
    }

    /** @test */
    public function the_text_has_a_minimum_length_of_one_character(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Text must be at least 1 character(s) long.');

        $block = new SectionBlock();
        $block->text('');

        $block->toArray();
    }

    /** @test */
    public function the_text_cannot_exceed_3000_characters(): void
    {
        $block = new SectionBlock();
        $block->text(str_repeat('a', 3001));

        $this->assertSame([
            'type' => 'section',
            'text' => [
                'type' => 'plain_text',
                'text' => str_repeat('a', 2997).'...',
            ],
        ], $block->toArray());
    }

    /** @test */
    public function the_text_can_be_customized(): void
    {
        $block = new SectionBlock();
        $block->text('Location: 123 Main Street, New York, NY 10010')->markdown();

        $this->assertSame([
            'type' => 'section',
            'text' => [
                'type' => 'mrkdwn',
                'text' => 'Location: 123 Main Street, New York, NY 10010',
            ],
        ], $block->toArray());
    }

    /** @test */
    public function it_does_not_allow_more_than_ten_fields(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('There is a maximum of 10 fields in each section block.');

        $block = new SectionBlock();
        for ($i = 0; $i < 11; $i++) {
            $block->field('Location: 123 Main Street, New York, NY 10010');
        }

        $block->toArray();
    }

    /** @test */
    public function a_field_cannot_exceed_2000_characters(): void
    {
        $block = new SectionBlock();
        $block->field(str_repeat('a', 2001));

        $this->assertSame([
            'type' => 'section',
            'fields' => [
                [
                    'type' => 'plain_text',
                    'text' => str_repeat('a', 1997).'...',
                ],
            ],
        ], $block->toArray());
    }

    /** @test */
    public function a_field_can_be_customized(): void
    {
        $block = new SectionBlock();
        $block->field('Location: 123 Main Street, New York, NY 10010')->markdown();

        $this->assertSame([
            'type' => 'section',
            'fields' => [
                [
                    'type' => 'mrkdwn',
                    'text' => 'Location: 123 Main Street, New York, NY 10010',
                ],
            ],
        ], $block->toArray());
    }

    /** @test */
    public function it_can_manually_specify_the_block_id_field(): void
    {
        $block = new SectionBlock();
        $block->text('Location: 123 Main Street, New York, NY 10010');
        $block->id('section1');

        $this->assertSame([
            'type' => 'section',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Location: 123 Main Street, New York, NY 10010',
            ],
            'block_id' => 'section1',
        ], $block->toArray());
    }

    /** @test */
    public function the_block_id_field_cannot_exceed_255_characters(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Maximum length for the block_id field is 255 characters.');

        $block = new SectionBlock();
        $block->text('Location: 123 Main Street, New York, NY 10010');
        $block->id(str_repeat('a', 256));

        $block->toArray();
    }

    /** @test */
    public function it_can_specify_an_accessory_element(): void
    {
        $block = new SectionBlock();
        $block->text('Location: 123 Main Street, New York, NY 10010');
        $block->accessory(new ImageElement('https://example.com/image.png', 'Image'));

        $this->assertSame([
            'type' => 'section',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Location: 123 Main Street, New York, NY 10010',
            ],
            'accessory' => [
                'type' => 'image',
                'image_url' => 'https://example.com/image.png',
                'alt_text' => 'Image',
            ],
        ], $block->toArray());
    }
}
