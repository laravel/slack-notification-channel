<?php

namespace Illuminate\Tests\Notifications\Slack\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Notifications\Slack\SlackMessage;
use Illuminate\Notifications\Slack\SlackRoute;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Tests\Notifications\Slack\SlackChannelTestNotifiable;
use Illuminate\Tests\Notifications\Slack\SlackChannelTestNotification;
use Illuminate\Tests\Notifications\Slack\TestCase;

class SlackChannelTest extends TestCase
{
    /** @test */
    public function the_route_notification_for_slack_method_describes_the_channel_using_a_string(): void
    {
        Config::set('services.slack.notifications.bot_user_oauth_token', 'config-set-token');

        $this->slackChannel->send(
            new SlackChannelTestNotifiable('example-channel'),
            new SlackChannelTestNotification(function (SlackMessage $message) {
                $message->text('Content')->to('ignored-channel');
            })
        );

        Http::assertSent(function (Request $request) {
            $this->assertEquals($request->url(), 'https://slack.com/api/chat.postMessage');
            $this->assertEquals($request->header('Authorization')[0], 'Bearer config-set-token');
            $this->assertEquals($request->header('Content-Type')[0], 'application/json');
            $this->assertSame(json_decode($request->body(), true), [
                'channel' => 'example-channel',
                'text' => 'Content',
            ]);

            return true;
        });
    }

    /** @test */
    public function the_route_notification_for_slack_method_describes_the_channel_using_a_slack_route_instance(): void
    {
        Config::set('services.slack.notifications.bot_user_oauth_token', 'config-set-token');

        $this->slackChannel->send(
            new SlackChannelTestNotifiable(SlackRoute::make('route-set-channel')),
            new SlackChannelTestNotification(function (SlackMessage $message) {
                $message->text('Content');
            })
        );

        Http::assertSent(function (Request $request) {
            $this->assertEquals($request->url(), 'https://slack.com/api/chat.postMessage');
            $this->assertEquals($request->header('Authorization')[0], 'Bearer config-set-token');
            $this->assertEquals($request->header('Content-Type')[0], 'application/json');
            $this->assertSame(json_decode($request->body(), true), [
                'channel' => 'route-set-channel',
                'text' => 'Content',
            ]);

            return true;
        });
    }

    /** @test */
    public function the_route_notification_for_slack_method_describes_the_channel_and_token_using_a_slack_route_instance(): void
    {
        Config::set('services.slack.notifications.bot_user_oauth_token', 'config-set-token');

        $this->slackChannel->send(
            new SlackChannelTestNotifiable(SlackRoute::make('route-set-channel', 'route-set-token')),
            new SlackChannelTestNotification(function (SlackMessage $message) {
                $message->text('Content');
            })
        );

        Http::assertSent(function (Request $request) {
            $this->assertEquals($request->url(), 'https://slack.com/api/chat.postMessage');
            $this->assertEquals($request->header('Authorization')[0], 'Bearer route-set-token');
            $this->assertEquals($request->header('Content-Type')[0], 'application/json');
            $this->assertSame(json_decode($request->body(), true), [
                'channel' => 'route-set-channel',
                'text' => 'Content',
            ]);

            return true;
        });
    }

    /** @test */
    public function the_route_notification_for_slack_method_only_describes_the_token_using_a_slack_route_instance(): void
    {
        Config::set('services.slack.notifications.bot_user_oauth_token', 'ignored-token');

        $this->slackChannel->send(
            new SlackChannelTestNotifiable(SlackRoute::make(null, 'route-set-token')),
            new SlackChannelTestNotification(function (SlackMessage $message) {
                $message->text('Content')->to('notification-channel');
            })
        );

        Http::assertSent(function (Request $request) {
            $this->assertEquals($request->url(), 'https://slack.com/api/chat.postMessage');
            $this->assertEquals($request->header('Authorization')[0], 'Bearer route-set-token');
            $this->assertEquals($request->header('Content-Type')[0], 'application/json');
            $this->assertSame(json_decode($request->body(), true), [
                'channel' => 'notification-channel',
                'text' => 'Content',
            ]);

            return true;
        });
    }

    /** @test */
    public function the_route_notification_for_slack_method_does_not_describe_anything(): void
    {
        Config::set('services.slack.notifications.bot_user_oauth_token', 'config-set-token');
        Config::set('services.slack.notifications.channel', 'config-set-channel');

        $this->slackChannel->send(
            new SlackChannelTestNotifiable(),
            new SlackChannelTestNotification(function (SlackMessage $message) {
                $message->text('Content');
            })
        );

        Http::assertSent(function (Request $request) {
            $this->assertEquals($request->url(), 'https://slack.com/api/chat.postMessage');
            $this->assertEquals($request->header('Authorization')[0], 'Bearer config-set-token');
            $this->assertEquals($request->header('Content-Type')[0], 'application/json');
            $this->assertSame(json_decode($request->body(), true), [
                'channel' => 'config-set-channel',
                'text' => 'Content',
            ]);

            return true;
        });
    }

    /** @test */
    public function it_prefers_the_notification_defined_channel_over_the_config_defined_channel(): void
    {
        Config::set('services.slack.notifications.bot_user_oauth_token', 'config-set-token');
        Config::set('services.slack.notifications.channel', 'config-set-channel');

        $this->slackChannel->send(
            new SlackChannelTestNotifiable(),
            new SlackChannelTestNotification(function (SlackMessage $message) {
                $message->text('Content')->to('notification-channel');
            })
        );

        Http::assertSent(function (Request $request) {
            $this->assertEquals($request->url(), 'https://slack.com/api/chat.postMessage');
            $this->assertEquals($request->header('Authorization')[0], 'Bearer config-set-token');
            $this->assertEquals($request->header('Content-Type')[0], 'application/json');
            $this->assertSame(json_decode($request->body(), true), [
                'channel' => 'notification-channel',
                'text' => 'Content',
            ]);

            return true;
        });
    }

    /** @test */
    public function it_throws_an_exception_when_the_route_notification_for_slack_method_does_not_provide_a_channel_and_the_notification_and_config_do_not_either(): void
    {
        Config::set('services.slack.notifications.bot_user_oauth_token', 'config-set-token');

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Slack notification channel is not set.');

        $this->slackChannel->send(
            new SlackChannelTestNotifiable(),
            new SlackChannelTestNotification(function (SlackMessage $message) {
                $message->text('Content');
            })
        );
    }

    /** @test */
    public function it_throws_an_exception_when_the_route_notification_for_slack_method_does_not_provide_a_token_and_the_config_does_not_either(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Slack API authentication token is not set.');

        $this->slackChannel->send(
            new SlackChannelTestNotifiable(SlackRoute::make('laravel-channel')),
            new SlackChannelTestNotification(function (SlackMessage $message) {
                $message->text('Content');
            })
        );
    }
}
