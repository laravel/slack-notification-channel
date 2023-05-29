<?php

namespace Illuminate\Notifications\Slack\BlockKit\Composites;

use Illuminate\Notifications\Slack\Contracts\ObjectContract;
use InvalidArgumentException;

class PlainTextOnlyTextObject implements ObjectContract
{
    /**
     * The formatting to use for this text object.
     */
    protected string $text;

    /**
     * Indicates whether emojis in a text field should be escaped into the colon emoji format.
     */
    protected ?bool $emoji = null;

    /**
     * Create a new plain text only text object instance.
     */
    public function __construct(string $text, int $maxLength = 3000, int $minLength = 1)
    {
        if (strlen($text) < $minLength) {
            throw new InvalidArgumentException('Text must be at least '.$minLength.' character(s) long.');
        }

        if (strlen($text) > $maxLength) {
            $text = substr($text, 0, $maxLength - 3).'...';
        }

        $this->text = $text;
    }

    /**
     * Indicate that emojis should be escaped into the colon emoji format.
     */
    public function emoji(): self
    {
        $this->emoji = true;

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $optionalFields = array_filter([
            'emoji' => $this->emoji,
        ]);

        return array_merge([
            'type' => 'plain_text',
            'text' => $this->text,
        ], $optionalFields);
    }
}
