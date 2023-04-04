<?php

namespace Illuminate\Notifications\Slack;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class SlackChannel
{
    /**
     * The HTTP factory instance.
     *
     * @var \Illuminate\Http\Client\Factory
     */
    protected $http;

    /**
     * Create a new Slack channel instance.
     *
     * @return void
     */
    public function __construct(Factory $http)
    {
        $this->http = $http;
    }

    /**
     * Send the given notification.
     */
    public function send(mixed $notifiable, Notification $notification): ?Response
    {
        $route = $this->determineRoute($notifiable, $notification);

        $message = $notification->toSlack($notifiable);

        $payload = $this->buildJsonPayload($message, $route);

        if (! $route->token || ! $payload['channel']) {
            return null;
        }

        return $this->http->asJson()
            ->withToken($route->token)
            ->post('https://slack.com/api/chat.postMessage', $payload);
    }

    /**
     * Build up a JSON payload for the Slack chat.postMessage API.
     *
     * @param  \Illuminate\Notifications\Slack\SlackMessage  $message
     * @param  \Illuminate\Notifications\Slack\SlackRoute  $route
     */
    protected function buildJsonPayload(SlackMessage $message, SlackRoute $route): array
    {
        $payload = $message->toArray();

        return array_merge($payload, [
            'channel' => $route->channel ?? $payload['channel'] ?? Config::get('services.slack.notifications.channel'),
        ]);
    }

    /**
     * Determine the API Token and Channel that the Notification should be posted to.
     *
     * @return \Illuminate\Notifications\Slack\SlackRoute
     */
    protected function determineRoute(mixed $notifiable, Notification $notification): SlackRoute
    {
        $route = $notifiable->routeNotificationFor('slack', $notification);

        // When the route is a string, we will assume it is a channel name
        // and will use the default API token for the application.
        if (is_string($route)) {
            return SlackRoute::make($route, Config::get('services.slack.notifications.bot_user_oauth_token'));
        }

        return SlackRoute::make(
            $route->channel ?? null,
            $route->token ?? Config::get('services.slack.notifications.bot_user_oauth_token'),
        );
    }
}