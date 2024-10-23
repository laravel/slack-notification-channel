<?php

namespace Illuminate\Notifications\Slack\BlockKit;


use Illuminate\Notifications\Slack\Contracts\BuilderContract;
use JsonException;

class Builder implements BuilderContract
{
    protected string $payload = "[]";

    public function payload(string $payload): self{
        $this->payload = $payload;

        return $this;
    }

    /**
     * @throws JsonException
     */
    public function toArray(): array
    {
        $array = json_decode($this->payload, true, flags: JSON_THROW_ON_ERROR);

        return array_key_exists('blocks', $array) ? $array['blocks'][0] : $array;
    }
}
