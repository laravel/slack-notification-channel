<?php

namespace Illuminate\Notifications\Slack\BlockKit\Elements\Selects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Slack\BlockKit\Composites\TextObject;
use Illuminate\Support\Str;

class SelectOption implements Arrayable
{
    /**
     * Text of the options.
     */
    private TextObject $text;

    /**
     * Value of the option.
     */
    private string $value;

    public function __construct(string $text, $value)
    {
        $this->text($text);
        $this->value($value);
    }

    /**
     * Sets the select text value.
     */
    private function text(string $text): void
    {
        $this->text = new TextObject($text, 75);
    }

    /**
     * Sets the select value.
     */
    private function value($value): void
    {
        $value = Str::lower($value);
        $value = preg_replace('/[^a-z0-9_\-.]/', '', $value);

        $this->value = $value;
    }

    public function toArray(): array
    {
        return [
            'text' => $this->text->toArray(),
            'value' => $this->value,
        ];
    }
}
