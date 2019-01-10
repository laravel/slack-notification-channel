<?php

namespace Illuminate\Notifications;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;

class SlackChannelServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        Notification::extend('slack', function ($app) {
            return new Channels\SlackWebhookChannel(new HttpClient);
        });
    }
}
