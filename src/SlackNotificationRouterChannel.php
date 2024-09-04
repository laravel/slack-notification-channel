<?php

namespace Illuminate\Notifications;

use Illuminate\Notifications\Channels\SlackWebhookChannel;
use Illuminate\Notifications\Slack\SlackChannel as SlackWebApiChannel;
use Illuminate\Support\Str;
use Psr\Http\Message\UriInterface;

class SlackNotificationRouterChannel
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new Slack notification router channel.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function send($notifiable, Notification $notification)
    {
        $route = $notifiable->routeNotificationFor('slack', $notification);

        if ($route === false) {
            return;
        }

        return $this->determineChannel($route)->send($notifiable, $notification);
    }

    /**
     * Determine which channel the Slack notification should be routed to.
     *
     * @param  mixed  $route
     * @return \Illuminate\Notifications\Channels\SlackWebhookChannel|\Illuminate\Notifications\Slack\SlackChannel
     */
    protected function determineChannel($route)
    {
        if ($route instanceof UriInterface) {
            return $this->app->make(SlackWebhookChannel::class);
        }

        if (is_string($route) && Str::startsWith($route, ['http://', 'https://'])) {
            return $this->app->make(SlackWebhookChannel::class);
        }

        return $this->app->make(SlackWebApiChannel::class);
    }
}
