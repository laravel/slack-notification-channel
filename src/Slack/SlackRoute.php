<?php

namespace Illuminate\Notifications\Slack;

class SlackRoute
{
    /**
     * The channel to send the notification to.
     *
     * Overrides the notification-defined channel (if any).
     */
    public ?string $channel;

    /**
     * The OAuth "workspace" token to use for sending the notification.
     *
     * Overrides the config-defined token (if any).
     */
    public ?string $token;

    /**
     * Create a new Slack route instance.
     */
    public function __construct(?string $channel = null, ?string $token = null)
    {
        $this->channel = $channel;
        $this->token = $token;
    }

    /**
     * Fluently create a new Slack route instance.
     */
    public static function make(?string $channel = null, ?string $token = null): self
    {
        return new self($channel, $token);
    }
}
