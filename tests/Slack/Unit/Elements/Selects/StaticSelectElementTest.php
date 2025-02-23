<?php

namespace Illuminate\Tests\Notifications\Slack\Unit\Elements\Selects;

use Illuminate\Notifications\Slack\BlockKit\Elements\Selects\StaticSelectElement;
use Illuminate\Tests\Notifications\Slack\TestCase;

class StaticSelectElementTest extends TestCase
{
    /** @test */
    public function test_it_can_add_initial_option(): void
    {
        $select = new StaticSelectElement();
        $select->id('initial_option_select');
        $select->addOption('Option A', 'option_a');
        $select->initialOption('option_a');

        $this->assertEquals([
            'type' => 'static_select',
            'action_id' => 'initial_option_select',
            'options' => [
                [
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Option A',
                    ],
                    'value' => 'option_a',
                ],
            ],
            'initial_option' => [
                'text' => [
                    'type' => 'plain_text',
                    'text' => 'Option A',
                ],
                'value' => 'option_a',
            ],
        ], $select->toArray());
    }

    /** @test */
    public function test_it_can_enable_focus_on_load(): void
    {
        $select = new StaticSelectElement();
        $select->id('enable_focus');
        $select->focus(true);

        $this->assertEquals([
            'type' => 'static_select',
            'action_id' => 'enable_focus',
            'focus_on_load' => true,
            'options' => [],
        ], $select->toArray());
    }

    /** @test */
    public function test_it_rejects_invalid_placeholder_text(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Text must be at least 1 character(s) long.');

        $select = new StaticSelectElement();
        $select->id('invalid_placeholder');
        $select->placeholder('');
    }

    /** @test */
    public function test_it_can_add_multiple_options(): void
    {
        $select = new StaticSelectElement();
        $select->id('multi_select');
        $select->addOption('Option A', 'option_a');
        $select->addOption('Option B', 'option_b');
        $select->addOption('Option C', 'option_c');

        $this->assertEquals([
            'type' => 'static_select',
            'action_id' => 'multi_select',
            'options' => [
                [
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Option A',
                    ],
                    'value' => 'option_a',
                ],
                [
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Option B',
                    ],
                    'value' => 'option_b',
                ],
                [
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Option C',
                    ],
                    'value' => 'option_c',
                ],
            ],
        ], $select->toArray());
    }

    /** @test */
    public function test_it_can_set_placeholder(): void
    {
        $select = new StaticSelectElement();
        $select->id('placeholder_select');
        $select->placeholder('Choose an option');

        $this->assertEquals([
            'type' => 'static_select',
            'action_id' => 'placeholder_select',
            'placeholder' => [
                'type' => 'plain_text',
                'text' => 'Choose an option',
            ],
            'options' => [],
        ], $select->toArray());
    }

    /** @test */
    public function test_it_can_disable_focus_on_load(): void
    {
        $select = new StaticSelectElement();
        $select->id('disable_focus');
        $select->focus(false);

        $this->assertEquals([
            'type' => 'static_select',
            'action_id' => 'disable_focus',
            'focus_on_load' => false,
            'options' => [],
        ], $select->toArray());
    }

    /** @test */
    public function test_it_rejects_initial_option_when_no_options_available(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Unknown option value: non_existent_option.');

        $select = new StaticSelectElement();
        $select->id('no_options');
        $select->initialOption('non_existent_option');
    }
}
