<?php

namespace Illuminate\Notifications;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class SlackChannelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('slack', function ($app) {
                return new SlackNotificationRouterChannel($app);
            });
        });
    }
}
