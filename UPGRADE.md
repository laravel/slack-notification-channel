# Upgrade Guide

## Upgrading To 3.0 From 2.x

### New Slack Notifications API

PR: https://github.com/laravel/slack-notification-channel/pull/64

Slack Notifications Channels 3.0 introduces a brand new way of writing Slack Notifications via the Slack BlockKit API. Previous forms of Slack notifications are still supported; however, to upgrade to the BlockKit API, you are free to [consult the Laravel documentation](https://laravel.com/docs/10.x/notifications#slack-notifications) and rewrite your notifications in the new format.
