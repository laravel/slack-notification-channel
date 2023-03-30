<?php

namespace Illuminate\Tests\Notifications\Slack;

use Closure;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\SlackChannel;
use Illuminate\Notifications\Slack\SlackChannelServiceProvider;
use Illuminate\Notifications\Slack\SlackMessage;
use Illuminate\Support\Facades\Http;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @var \Illuminate\Http\Client\Factory
     */
    protected $http;

    /**
     * @var \Illuminate\Notifications\Slack\SlackChannel
     */
    protected $slackChannel;

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            SlackChannelServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->http = Http::fake();
        $this->slackChannel = new SlackChannel($this->http);
    }

    protected function buildNotification(Closure $callback): Notification
    {
        return new class($callback) extends Notification
        {
            private Closure $callback;

            public function __construct(Closure $callback)
            {
                $this->callback = $callback;
            }

            public function toSlack($notifiable)
            {
                return tap(new SlackMessage(), $this->callback);
            }
        };
    }
}
