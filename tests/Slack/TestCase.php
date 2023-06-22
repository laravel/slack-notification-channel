<?php

namespace Illuminate\Tests\Notifications\Slack;

use Illuminate\Notifications\Slack\SlackChannel;
use Illuminate\Notifications\SlackChannelServiceProvider;
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
}
