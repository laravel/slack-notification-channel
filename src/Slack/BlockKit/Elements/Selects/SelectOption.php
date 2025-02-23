<?php

namespace Illuminate\Notifications\Slack\BlockKit\Elements\Selects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Slack\BlockKit\Composites\TextObject;
use Illuminate\Support\Str;

class SelectOption implements Arrayable
{
    /**
     * The option text.
     */
    protected TextObject $text;

    /**
     * The option value.
     */
    protected string $value;

    /**
     * Create a new select option instance.
     */
    public function __construct(string $text, mixed $value)
    {
        $this->text($text);
        $this->value($value);
    }

    /**
     * Set the option's text value.
     */
    protected function text(string $text): void
    {
        $this->text = new TextObject($text, 75);
    }

    /**
     * Set the option's value.
     */
    protected function value($value): void
    {
        $this->value = preg_replace('/[^a-z0-9_\-.]/', '', Str::lower($value));
    }

    /**
     * Convert the select option to an array.
     */
    public function toArray(): array
    {
        return [
            'text' => $this->text->toArray(),
            'value' => $this->value,
        ];
    }
}
