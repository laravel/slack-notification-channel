<?php

namespace Illuminate\Tests\Notifications\Slack\Unit\Elements;

use Illuminate\Notifications\Slack\BlockKit\Elements\ImageElement;
use Illuminate\Tests\Notifications\Slack\TestCase;
use LogicException;

class ImageElementTest extends TestCase
{
    /** @test */
    public function it_is_arrayable(): void
    {
        $element = new ImageElement('http://placekitten.com/700/500', 'Multiple cute kittens');

        $this->assertSame([
            'type' => 'image',
            'image_url' => 'http://placekitten.com/700/500',
            'alt_text' => 'Multiple cute kittens',
        ], $element->toArray());
    }

    /** @test */
    public function the_alt_text_is_required(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Alt text is required for an image element.');

        $element = new ImageElement('http://placekitten.com/700/500');

        $element->toArray();
    }

    /** @test */
    public function the_alt_text_is_optional_during_object_instantiation(): void
    {
        $element = new ImageElement('http://placekitten.com/700/500');
        $element->alt('Some alt text');

        $this->assertSame([
            'type' => 'image',
            'image_url' => 'http://placekitten.com/700/500',
            'alt_text' => 'Some alt text',
        ], $element->toArray());
    }
}
