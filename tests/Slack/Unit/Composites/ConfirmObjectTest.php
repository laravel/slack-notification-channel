<?php

namespace Illuminate\Tests\Notifications\Slack\Unit\Composites;

use Illuminate\Notifications\Slack\BlockKit\Composites\ConfirmObject;
use Illuminate\Tests\Notifications\Slack\TestCase;

class ConfirmObjectTest extends TestCase
{
    /** @test */
    public function it_is_arrayable(): void
    {
        $object = new ConfirmObject();

        $this->assertSame([
            'title' => [
                'type' => 'plain_text',
                'text' => 'Are you sure?',
            ],
            'text' => [
                'type' => 'plain_text',
                'text' => 'Please confirm this action.',
            ],
            'confirm' => [
                'type' => 'plain_text',
                'text' => 'Yes',
            ],
            'deny' => [
                'type' => 'plain_text',
                'text' => 'No',
            ],
        ], $object->toArray());
    }

    /** @test */
    public function the_title_field_is_customizable(): void
    {
        $object = new ConfirmObject();
        $object->title('This is a custom title.');

        $this->assertSame([
            'title' => [
                'type' => 'plain_text',
                'text' => 'This is a custom title.',
            ],
            'text' => [
                'type' => 'plain_text',
                'text' => 'Please confirm this action.',
            ],
            'confirm' => [
                'type' => 'plain_text',
                'text' => 'Yes',
            ],
            'deny' => [
                'type' => 'plain_text',
                'text' => 'No',
            ],
        ], $object->toArray());
    }

    /** @test */
    public function the_title_gets_truncated_when_it_exceeds_100_characters(): void
    {
        $object = new ConfirmObject();
        $object->title(str_repeat('a', 101));

        $this->assertSame([
            'title' => [
                'type' => 'plain_text',
                'text' => str_repeat('a', 97).'...',
            ],
            'text' => [
                'type' => 'plain_text',
                'text' => 'Please confirm this action.',
            ],
            'confirm' => [
                'type' => 'plain_text',
                'text' => 'Yes',
            ],
            'deny' => [
                'type' => 'plain_text',
                'text' => 'No',
            ],
        ], $object->toArray());
    }

    /** @test */
    public function the_text_field_is_customizable(): void
    {
        $object = new ConfirmObject();
        $object->text('This is some custom text.');

        $this->assertSame([
            'title' => [
                'type' => 'plain_text',
                'text' => 'Are you sure?',
            ],
            'text' => [
                'type' => 'plain_text',
                'text' => 'This is some custom text.',
            ],
            'confirm' => [
                'type' => 'plain_text',
                'text' => 'Yes',
            ],
            'deny' => [
                'type' => 'plain_text',
                'text' => 'No',
            ],
        ], $object->toArray());
    }

    /** @test */
    public function the_text_gets_truncated_when_it_exceeds_300_characters(): void
    {
        $objectA = new ConfirmObject(str_repeat('a', 301));

        $this->assertSame([
            'title' => [
                'type' => 'plain_text',
                'text' => 'Are you sure?',
            ],
            'text' => [
                'type' => 'plain_text',
                'text' => str_repeat('a', 297).'...',
            ],
            'confirm' => [
                'type' => 'plain_text',
                'text' => 'Yes',
            ],
            'deny' => [
                'type' => 'plain_text',
                'text' => 'No',
            ],
        ], $objectA->toArray());

        $objectB = new ConfirmObject();
        $objectB->text(str_repeat('b', 301));

        $this->assertSame([
            'title' => [
                'type' => 'plain_text',
                'text' => 'Are you sure?',
            ],
            'text' => [
                'type' => 'plain_text',
                'text' => str_repeat('b', 297).'...',
            ],
            'confirm' => [
                'type' => 'plain_text',
                'text' => 'Yes',
            ],
            'deny' => [
                'type' => 'plain_text',
                'text' => 'No',
            ],
        ], $objectB->toArray());
    }

    /** @test */
    public function the_confirm_field_is_customizable(): void
    {
        $object = new ConfirmObject();
        $object->confirm('Custom confirmation button.');

        $this->assertSame([
            'title' => [
                'type' => 'plain_text',
                'text' => 'Are you sure?',
            ],
            'text' => [
                'type' => 'plain_text',
                'text' => 'Please confirm this action.',
            ],
            'confirm' => [
                'type' => 'plain_text',
                'text' => 'Custom confirmation button.',
            ],
            'deny' => [
                'type' => 'plain_text',
                'text' => 'No',
            ],
        ], $object->toArray());
    }

    /** @test */
    public function the_confirm_field_is_gets_truncated_after_30_characters(): void
    {
        $object = new ConfirmObject();
        $object->confirm(str_repeat('a', 31));

        $this->assertSame([
            'title' => [
                'type' => 'plain_text',
                'text' => 'Are you sure?',
            ],
            'text' => [
                'type' => 'plain_text',
                'text' => 'Please confirm this action.',
            ],
            'confirm' => [
                'type' => 'plain_text',
                'text' => str_repeat('a', 27).'...',
            ],
            'deny' => [
                'type' => 'plain_text',
                'text' => 'No',
            ],
        ], $object->toArray());
    }

    /** @test */
    public function the_color_scheme_can_be_set_to_danger(): void
    {
        $object = new ConfirmObject();
        $object->danger();

        $this->assertSame([
            'title' => [
                'type' => 'plain_text',
                'text' => 'Are you sure?',
            ],
            'text' => [
                'type' => 'plain_text',
                'text' => 'Please confirm this action.',
            ],
            'confirm' => [
                'type' => 'plain_text',
                'text' => 'Yes',
            ],
            'deny' => [
                'type' => 'plain_text',
                'text' => 'No',
            ],
            'style' => 'danger',
        ], $object->toArray());
    }

    /** @test */
    public function the_deny_field_is_customizable(): void
    {
        $object = new ConfirmObject();
        $object->deny('Custom deny button.');

        $this->assertSame([
            'title' => [
                'type' => 'plain_text',
                'text' => 'Are you sure?',
            ],
            'text' => [
                'type' => 'plain_text',
                'text' => 'Please confirm this action.',
            ],
            'confirm' => [
                'type' => 'plain_text',
                'text' => 'Yes',
            ],
            'deny' => [
                'type' => 'plain_text',
                'text' => 'Custom deny button.',
            ],
        ], $object->toArray());
    }

    /** @test */
    public function the_deny_field_is_gets_truncated_after_30_characters(): void
    {
        $object = new ConfirmObject();
        $object->deny(str_repeat('a', 31));

        $this->assertSame([
            'title' => [
                'type' => 'plain_text',
                'text' => 'Are you sure?',
            ],
            'text' => [
                'type' => 'plain_text',
                'text' => 'Please confirm this action.',
            ],
            'confirm' => [
                'type' => 'plain_text',
                'text' => 'Yes',
            ],
            'deny' => [
                'type' => 'plain_text',
                'text' => str_repeat('a', 27).'...',
            ],
        ], $object->toArray());
    }
}
