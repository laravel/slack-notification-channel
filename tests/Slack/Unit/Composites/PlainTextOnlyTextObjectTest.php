<?php

namespace Illuminate\Tests\Notifications\Slack\Unit\Composites;

use Illuminate\Notifications\Slack\BlockKit\Composites\PlainTextOnlyTextObject;
use Illuminate\Tests\Notifications\Slack\TestCase;
use LogicException;

class PlainTextOnlyTextObjectTest extends TestCase
{
    public function test_it_is_arrayable(): void
    {
        $object = new PlainTextOnlyTextObject('A message *with some bold text* and _some italicized text_.');

        $this->assertSame([
            'type' => 'plain_text',
            'text' => 'A message *with some bold text* and _some italicized text_.',
        ], $object->toArray());
    }

    public function test_the_text_has_a_minimum_length_of_1_character(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Text must be at least 1 character(s) long.');

        new PlainTextOnlyTextObject('');
    }

    public function test_the_text_gets_truncated_when_it_exceeds_3000_characters(): void
    {
        $object = new PlainTextOnlyTextObject(str_repeat('a', 3001));

        $this->assertSame([
            'type' => 'plain_text',
            'text' => str_repeat('a', 2997).'...',
        ], $object->toArray());
    }

    public function test_truncating_does_not_split_multibyte_characters(): void
    {
        // 🪓 is 4 bytes in UTF-8, so the byte-based truncation point lands
        // mid-character; truncating there must not produce invalid UTF-8.
        $object = new PlainTextOnlyTextObject(str_repeat('🪓', 751));

        $text = $object->toArray()['text'];

        $this->assertTrue(mb_check_encoding($text, 'UTF-8'));
        $this->assertNotFalse(json_encode($object->toArray()));
    }

    public function test_it_can_indicate_that_emojis_should_be_escaped_into_the_colon_emoji_format(): void
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
