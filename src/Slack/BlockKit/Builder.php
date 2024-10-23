<?php

namespace Illuminate\Notifications\Slack\BlockKit;

use Illuminate\Contracts\Support\Arrayable;
use JsonException;

class Builder implements Arrayable
{
    public function __construct(
        protected string $payload = '[]'
    ) {
    }

    /**
     * Fluently create a new Builder instance.
     */
    public static function make(string $payload): self
    {
        return new self($payload);
    }

    /**
     * @throws JsonException
     */
    public function toArray(): array
    {
        $array = json_decode($this->payload, true, flags: JSON_THROW_ON_ERROR);

        return array_key_exists('blocks', $array) ? $array['blocks'] : $array;
    }
}
