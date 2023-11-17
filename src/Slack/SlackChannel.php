<?php

namespace Illuminate\Notifications\Slack;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use LogicException;
use RuntimeException;

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
     * @param  \Illuminate\Http\Client\Factory  $http
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

        if (! $payload['channel']) {
            throw new LogicException('Slack notification channel is not set.');
        }

        if (! $route->token) {
            throw new LogicException('Slack API authentication token is not set.');
        }

        $response = $this->http->asJson()
            ->withToken($route->token)
            ->post('https://slack.com/api/chat.postMessage', $payload)
            ->throw();

        if ($response->successful() && $response->json('ok') === false) {
            throw new RuntimeException('Slack API call failed with error ['.$response->json('error').'].');
        }

        return $response;
    }

    /**
     * Build the JSON payload for the Slack chat.postMessage API.
     */
    protected function buildJsonPayload(SlackMessage $message, SlackRoute $route): array
    {
        $payload = $message->toArray();

        return array_merge($payload, [
            'channel' => $route->channel ?? $payload['channel'] ?? Config::get('services.slack.notifications.channel'),
        ]);
    }

    /**
     * Determine the API Token and Channel that the notification should be posted to.
     */
    protected function determineRoute(mixed $notifiable, Notification $notification): SlackRoute
    {
        $route = $notifiable->routeNotificationFor('slack', $notification);

        // When the route is a string, we will assume it is a channel name and will use the default API token for the application...
        if (is_string($route)) {
            return SlackRoute::make($route, Config::get('services.slack.notifications.bot_user_oauth_token'));
        }

        return SlackRoute::make(
            $route->channel ?? null,
            $route->token ?? Config::get('services.slack.notifications.bot_user_oauth_token'),
        );
    }
}
