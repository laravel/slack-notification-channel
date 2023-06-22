<?php

namespace Illuminate\Tests\Notifications\Slack\Unit\Composites;

use Illuminate\Notifications\Slack\BlockKit\Composites\TextObject;
use Illuminate\Tests\Notifications\Slack\TestCase;
use LogicException;

class TextObjectTest extends TestCase
{
    /** @test */
    public function it_is_arrayable(): void
    {
        $object = new TextObject('A message *with some bold text* and _some italicized text_.');

        $this->assertSame([
            'type' => 'plain_text',
            'text' => 'A message *with some bold text* and _some italicized text_.',
        ], $object->toArray());
    }

    /** @test */
    public function it_can_be_a_markdown_text_field(): void
    {
        $object = new TextObject('A message *with some bold text* and _some italicized text_.');
        $object->markdown();

        $this->assertSame([
            'type' => 'mrkdwn',
            'text' => 'A message *with some bold text* and _some italicized text_.',
        ], $object->toArray());
    }

    /** @test */
    public function the_text_has_a_minimum_length_of_1_character(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Text must be at least 1 character(s) long.');

        new TextObject('');
    }

    /** @test */
    public function the_text_gets_truncated_when_it_exceeds_3000_characters(): void
    {
        $object = new TextObject(str_repeat('a', 3001));

        $this->assertSame([
            'type' => 'plain_text',
            'text' => str_repeat('a', 2997).'...',
        ], $object->toArray());
    }

    /** @test */
    public function it_can_indicate_that_emojis_should_be_escaped_into_the_colon_emoji_format(): void
    {
        $object = new TextObject('Spooky time! 👻');
        $object->emoji();

        $this->assertSame([
            'type' => 'plain_text',
            'text' => 'Spooky time! 👻',
            'emoji' => true,
        ], $object->toArray());
    }

    /** @test */
    public function it_cannot_indicate_that_emojis_should_be_escaped_into_the_colon_emoji_format_when_using_markdown(): void
    {
        $object = new TextObject('Spooky time! 👻');
        $object->markdown()->emoji();

        $this->assertSame([
            'type' => 'mrkdwn',
            'text' => 'Spooky time! 👻',
        ], $object->toArray());
    }

    /** @test */
    public function it_can_indicate_that_auto_conversion_into_clickable_anchors_should_be_skipped(): void
    {
        $object = new TextObject('A message *with some bold text* and _some italicized text_.');
        $object->markdown()->verbatim();

        $this->assertSame([
            'type' => 'mrkdwn',
            'text' => 'A message *with some bold text* and _some italicized text_.',
            'verbatim' => true,
        ], $object->toArray());
    }

    /** @test */
    public function it_cannot_indicate_that_auto_conversion_into_clickable_anchors_should_be_skipped_when_using_plaintext(): void
    {
        $object = new TextObject('A message *with some bold text* and _some italicized text_.');
        $object->verbatim();

        $this->assertSame([
            'type' => 'plain_text',
            'text' => 'A message *with some bold text* and _some italicized text_.',
        ], $object->toArray());
    }
}
