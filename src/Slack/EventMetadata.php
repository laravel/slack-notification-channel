<?php

namespace Illuminate\Notifications\Slack;

use Illuminate\Contracts\Support\Arrayable;

class EventMetadata implements Arrayable
{
    /**
     * The event type for the metadata payload.
     */
    protected string $type;

    /**
     * The metadata payload.
     */
    protected array $payload;

    /**
     * Create a new event metadata instance.
     *
     * @param  string  $type
     * @param  array  $payload
     * @return void
     */
    public function __construct(string $type, array $payload = [])
    {
        $this->type = $type;
        $this->payload = $payload;
    }

    /**
     * Fluently create a new event metadata instance.
     */
    public static function make(string $type, array $payload = []): self
    {
        return new self($type, $payload);
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return [
            'event_type' => $this->type,
            'event_payload' => $this->payload,
        ];
    }
}
