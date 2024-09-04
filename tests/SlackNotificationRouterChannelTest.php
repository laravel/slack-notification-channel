<?php

namespace Illuminate\Tests\Notifications;

use Closure;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Container\Container;
use Illuminate\Notifications\Channels\SlackWebhookChannel;
use Illuminate\Notifications\Slack\SlackChannel as SlackWebApiChannel;
use Illuminate\Notifications\SlackNotificationRouterChannel;
use Illuminate\Tests\Notifications\Slack\SlackChannelTestNotifiable;
use Illuminate\Tests\Notifications\Slack\SlackChannelTestNotification;
use PHPUnit\Framework\TestCase;

class SlackNotificationRouterChannelTest extends TestCase
{
    /** @test */
    public function it_routes_the_notification_to_the_webhook_channel_when_the_notifiable_route_is_a_string_url(): void
    {
        $app = new Container();
        $app->bind(SlackWebhookChannel::class, fn () => new FakeSlackChannel(function ($notifiable, $notification) {
            $this->assertEquals('http://example.com', $notifiable->routeNotificationFor('slack', $notification));
        }));
        $app->bind(SlackWebApiChannel::class, fn () => new FakeSlackChannel(function () {
            $this->fail('The Slack WebAPI Channel should not have been called.');
        }));

        $channel = new SlackNotificationRouterChannel($app);

        $channel->send(new SlackChannelTestNotifiable('http://example.com'), new SlackChannelTestNotification());
    }

    /** @test */
    public function it_routes_the_notification_to_the_webhook_channel_when_the_notifiable_route_is_a_psr_url_instance(): void
    {
        $app = new Container();
        $app->bind(SlackWebhookChannel::class, fn () => new FakeSlackChannel(function ($notifiable, $notification) {
            $this->assertInstanceOf(Uri::class, $notifiable->routeNotificationFor('slack', $notification));
        }));
        $app->bind(SlackWebApiChannel::class, fn () => new FakeSlackChannel(function () {
            $this->fail('The Slack WebAPI Channel should not have been called.');
        }));

        $channel = new SlackNotificationRouterChannel($app);

        $channel->send(new SlackChannelTestNotifiable(new Uri('foo')), new SlackChannelTestNotification());
    }

    /** @test */
    public function it_routes_the_notification_to_the_web_api_channel_when_the_notifiable_route_is_not_an_url(): void
    {
        $app = new Container();
        $app->bind(SlackWebhookChannel::class, fn () => new FakeSlackChannel(function () {
            $this->fail('The Slack Webhook Channel should not have been called.');
        }));
        $app->bind(SlackWebApiChannel::class, fn () => new FakeSlackChannel(function ($notifiable, $notification) {
            $this->assertEquals('#general', $notifiable->routeNotificationFor('slack', $notification));
        }));

        $channel = new SlackNotificationRouterChannel($app);

        $channel->send(new SlackChannelTestNotifiable('#general'), new SlackChannelTestNotification());
    }

    /** @test */
    public function it_stops_sending_when_the_notifiable_route_is_false(): void
    {
        $app = new Container();
        $app->bind(SlackWebhookChannel::class, fn () => new FakeSlackChannel(function () {
            $this->fail('The Slack Webhook Channel should not have been called.');
        }));
        $app->bind(SlackWebApiChannel::class, fn () => new FakeSlackChannel(function () {
            $this->fail('The Slack WebAPI Channel should not have been called.');
        }));

        $channel = new SlackNotificationRouterChannel($app);

        $this->assertEquals(null, $channel->send(new SlackChannelTestNotifiable(false), new SlackChannelTestNotification()));
    }
}

class FakeSlackChannel
{
    protected $callback;

    public function __construct(Closure $callback)
    {
        $this->callback = $callback;
    }

    public function send($notifiable, $notification)
    {
        return call_user_func($this->callback, $notifiable, $notification);
    }
}
