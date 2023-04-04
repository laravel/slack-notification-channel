<?php

namespace Illuminate\Tests\Notifications\Slack;

use Illuminate\Notifications\Notifiable;

class SlackChannelTestNotifiable
{
    use Notifiable;

    protected $route;

    public function __construct($route = null)
    {
        $this->route = $route;
    }

    public function routeNotificationForSlack()
    {
        return $this->route;
    }
}
