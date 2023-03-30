<?php

namespace Illuminate\Notifications\Slack;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class SlackChannelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('slack', function ($app) {
                return new SlackChannel($app[HttpFactory::class]);
            });
        });
    }
}
