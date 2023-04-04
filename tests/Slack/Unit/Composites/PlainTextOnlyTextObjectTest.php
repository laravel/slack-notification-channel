<?php

namespace Illuminate\Tests\Notifications\Slack\Unit\Composites;

use Illuminate\Notifications\Slack\BlockKit\Composites\PlainTextOnlyTextObject;
use Illuminate\Tests\Notifications\Slack\TestCase;
use LogicException;

class PlainTextOnlyTextObjectTest extends TestCase
{
    /** @test */
    public function it_is_arrayable(): void
    {
        $object = new PlainTextOnlyTextObject('A message *with some bold text* and _some italicized text_.');

        $this->assertSame([
            'type' => 'plain_text',
            'text' => 'A message *with some bold text* and _some italicized text_.',
        ], $object->toArray());
    }

    /** @test */
    public function the_text_has_a_minimum_length_of_1_character(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Text must be at least 1 character(s) long.');

        new PlainTextOnlyTextObject('');
    }

    /** @test */
    public function the_text_gets_truncated_when_it_exceeds_3000_characters(): void
    {
        $object = new PlainTextOnlyTextObject(str_repeat('a', 3001));

        $this->assertSame([
            'type' => 'plain_text',
            'text' => str_repeat('a', 2997).'...',
        ], $object->toArray());
    }

    /** @test */
    public function it_can_indicate_that_emojis_should_be_escaped_into_the_colon_emoji_format(): void
    {
        $object = new PlainTextOnlyTextObject('Spooky time! 👻');
        $object->emoji();

        $this->assertSame([
            'type' => 'plain_text',
            'text' => 'Spooky time! 👻',
            'emoji' => true,
        ], $object->toArray());
    }
}
