<?php

namespace Illuminate\Tests\Notifications\Slack\Unit\Elements;

use Illuminate\Notifications\Slack\BlockKit\Composites\ConfirmObject;
use Illuminate\Notifications\Slack\BlockKit\Composites\PlainTextOnlyTextObject;
use Illuminate\Notifications\Slack\BlockKit\Elements\ButtonElement;
use Illuminate\Tests\Notifications\Slack\TestCase;
use InvalidArgumentException;

class ButtonElementTest extends TestCase
{
    /** @test */
    public function it_is_arrayable(): void
    {
        $element = new ButtonElement('Click Me');

        $this->assertSame([
            'type' => 'button',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Click Me',
            ],
            'action_id' => 'button_click-me',
        ], $element->toArray());
    }

    /** @test */
    public function the_maximum_text_length_is_75_characters(): void
    {
        $element = new ButtonElement(str_repeat('a', 250));

        $this->assertSame([
            'type' => 'button',
            'text' => [
                'type' => 'plain_text',
                'text' => str_repeat('a', 72).'...',
            ],
            'action_id' => 'button_'.str_repeat('a', 248),
        ], $element->toArray());
    }

    /** @test */
    public function the_text_can_be_customized(): void
    {
        $element = new ButtonElement('Click Me', function (PlainTextOnlyTextObject $textObject) {
            $textObject->emoji();
        });

        $this->assertSame([
            'type' => 'button',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Click Me',
                'emoji' => true,
            ],
            'action_id' => 'button_click-me',
        ], $element->toArray());
    }

    /** @test */
    public function the_action_id_can_be_customized(): void
    {
        $element = new ButtonElement('Click Me');
        $element->id('custom_action_id');

        $this->assertSame([
            'type' => 'button',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Click Me',
            ],
            'action_id' => 'custom_action_id',
        ], $element->toArray());
    }

    /** @test */
    public function the_action_id_cannot_exceed_255_characters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum length for the action_id field is 255 characters.');

        $element = new ButtonElement('Click Me');
        $element->id(str_repeat('a', 256));

        $element->toArray();
    }

    /** @test */
    public function it_can_have_an_url(): void
    {
        $element = new ButtonElement('Click Me');
        $element->url('https://laravel.com');

        $this->assertSame([
            'type' => 'button',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Click Me',
            ],
            'action_id' => 'button_click-me',
            'url' => 'https://laravel.com',
        ], $element->toArray());
    }

    /** @test */
    public function the_url_cannot_exceed_3000_characters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum length for the url field is 3000 characters.');

        $element = new ButtonElement('Click Me');
        $element->url(str_repeat('a', 3001));

        $element->toArray();
    }

    /** @test */
    public function it_can_have_a_value(): void
    {
        $element = new ButtonElement('Click Me');
        $element->value('click_me_123');

        $this->assertSame([
            'type' => 'button',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Click Me',
            ],
            'action_id' => 'button_click-me',
            'value' => 'click_me_123',
        ], $element->toArray());
    }

    /** @test */
    public function the_value_cannot_exceed_2000_characters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum length for the value field is 2000 characters.');

        $element = new ButtonElement('Click Me');
        $element->value(str_repeat('a', 2001));

        $element->toArray();
    }

    /** @test */
    public function it_can_have_the_primary_style(): void
    {
        $element = new ButtonElement('Click Me');
        $element->primary();

        $this->assertSame([
            'type' => 'button',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Click Me',
            ],
            'action_id' => 'button_click-me',
            'style' => 'primary',
        ], $element->toArray());
    }

    /** @test */
    public function it_can_have_the_danger_style(): void
    {
        $element = new ButtonElement('Click Me');
        $element->danger();

        $this->assertSame([
            'type' => 'button',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Click Me',
            ],
            'action_id' => 'button_click-me',
            'style' => 'danger',
        ], $element->toArray());
    }

    /** @test */
    public function it_can_have_a_confirmation_dialog(): void
    {
        $element = new ButtonElement('Click Me');
        $element->confirm('This will do some thing.')->deny('Yikes!');

        $this->assertSame([
            'type' => 'button',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Click Me',
            ],
            'action_id' => 'button_click-me',
            'confirm' => [
                'title' => [
                    'type' => 'plain_text',
                    'text' => 'Are you sure?',
                ],
                'text' => [
                    'type' => 'plain_text',
                    'text' => 'This will do some thing.',
                ],
                'confirm' => [
                    'type' => 'plain_text',
                    'text' => 'Yes',
                ],
                'deny' => [
                    'type' => 'plain_text',
                    'text' => 'Yikes!',
                ],
            ],
        ], $element->toArray());
    }

    /** @test */
    public function it_can_scope_the_confirmation_dialog_and_set_multiple_options(): void
    {
        $element = new ButtonElement('Click Me');
        $element->confirm('This will do some thing.', function (ConfirmObject $dialog) {
            $dialog->deny('Yikes!');
            $dialog->confirm('Woohoo!');
        });

        $this->assertSame([
            'type' => 'button',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Click Me',
            ],
            'action_id' => 'button_click-me',
            'confirm' => [
                'title' => [
                    'type' => 'plain_text',
                    'text' => 'Are you sure?',
                ],
                'text' => [
                    'type' => 'plain_text',
                    'text' => 'This will do some thing.',
                ],
                'confirm' => [
                    'type' => 'plain_text',
                    'text' => 'Woohoo!',
                ],
                'deny' => [
                    'type' => 'plain_text',
                    'text' => 'Yikes!',
                ],
            ],
        ], $element->toArray());
    }

    /** @test */
    public function it_can_have_an_accessibility_label(): void
    {
        $element = new ButtonElement('Click Me');
        $element->accessibilityLabel('Click Me Button');

        $this->assertSame([
            'type' => 'button',
            'text' => [
                'type' => 'plain_text',
                'text' => 'Click Me',
            ],
            'action_id' => 'button_click-me',
            'accessibility_label' => 'Click Me Button',
        ], $element->toArray());
    }

    /** @test */
    public function the_accessibility_label_cannot_exceed_75_characters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum length for the accessibility label is 75 characters.');

        $element = new ButtonElement('Click Me');
        $element->accessibilityLabel(str_repeat('a', 76));

        $element->toArray();
    }
}
