# Release Notes

## [Unreleased](https://github.com/laravel/slack-notification-channel/compare/v3.3.1...3.x)

## [v3.3.1](https://github.com/laravel/slack-notification-channel/compare/v3.3.0...v3.3.1) - 2024-08-17

* fix: do not notify when route is empty by [@TomaszOnePilot](https://github.com/TomaszOnePilot) in https://github.com/laravel/slack-notification-channel/pull/96

## [v3.3.0](https://github.com/laravel/slack-notification-channel/compare/v3.2.0...v3.3.0) - 2024-07-10

* Use new static analysis workflow by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/slack-notification-channel/pull/89
* feat: support threaded replies for SlackMessage by [@Ma-ve](https://github.com/Ma-ve) in https://github.com/laravel/slack-notification-channel/pull/94

## [v3.2.0](https://github.com/laravel/slack-notification-channel/compare/v3.1.1...v3.2.0) - 2024-01-15

* [3.x] Merge develop by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/laravel/slack-notification-channel/pull/88

## [v3.1.1](https://github.com/laravel/slack-notification-channel/compare/v3.1.0...v3.1.1) - 2024-01-06

* constructor method parameter declaration by [@slvler](https://github.com/slvler) in https://github.com/laravel/slack-notification-channel/pull/80
* constructor method parameter declaration by [@slvler](https://github.com/slvler) in https://github.com/laravel/slack-notification-channel/pull/82
* Support disabling slack route dynamically when using `SlackWebhookChannel` by [@marijoo](https://github.com/marijoo) in https://github.com/laravel/slack-notification-channel/pull/87

## [v3.1.0](https://github.com/laravel/slack-notification-channel/compare/v3.0.1...v3.1.0) - 2023-10-30

- Fix unfurlLinks and unfurlMedia methods by [@john-f-chamberlain](https://github.com/john-f-chamberlain) in https://github.com/laravel/slack-notification-channel/pull/79

## [v3.0.1](https://github.com/laravel/slack-notification-channel/compare/v3.0.0...v3.0.1) - 2023-07-25

- Add support for conditional Slack messages by [@maartenpaauw](https://github.com/maartenpaauw) in https://github.com/laravel/slack-notification-channel/pull/71

## [v3.0.0](https://github.com/laravel/slack-notification-channel/compare/v2.5.0...v3.0.0) - 2023-07-14

- Set buildJsonPayload method as public for next major release by [@BSN4](https://github.com/BSN4) in https://github.com/laravel/slack-notification-channel/pull/31
- Support BlockKit / WebAPI-based bot notifications by [@claudiodekker](https://github.com/claudiodekker) in https://github.com/laravel/slack-notification-channel/pull/64

## [v2.5.0](https://github.com/laravel/slack-notification-channel/compare/v2.4.0...v2.5.0) - 2023-01-12

### Added

- Laravel 10 support by @erikn69 in https://github.com/laravel/slack-notification-channel/pull/60

## [v2.4.0 (2022-01-12)](https://github.com/laravel/slack-notification-channel/compare/v2.3.1...v2.4.0)

### Changed

- Laravel 9 Support ([#53](https://github.com/laravel/slack-notification-channel/pull/53))

## [v2.3.1 (2021-01-26)](https://github.com/laravel/slack-notification-channel/compare/v2.3.0...v2.3.1)

### Fixed

- Clarify `timestamp` method ([#47](https://github.com/laravel/slack-notification-channel/pull/47))

## [v2.3.0 (2020-11-03)](https://github.com/laravel/slack-notification-channel/compare/v2.2.0...v2.3.0)

### Added

- PHP 8.0 support ([#45](https://github.com/laravel/slack-notification-channel/pull/45))
- Add callback id for attachments ([#43](https://github.com/laravel/slack-notification-channel/pull/43))

## [v2.2.0 (2020-08-25)](https://github.com/laravel/slack-notification-channel/compare/v2.1.0...v2.2.0)

### Added

- Laravel 8 support ([#42](https://github.com/laravel/slack-notification-channel/pull/42))

## [v2.1.0 (2020-06-30)](https://github.com/laravel/slack-notification-channel/compare/v2.0.2...v2.1.0)

### Added

- Guzzle 7 support ([#36](https://github.com/laravel/slack-notification-channel/pull/36))

## [v2.0.2 (2019-08-27)](https://github.com/laravel/slack-notification-channel/compare/v2.0.1...v2.0.2)

### Changed

- Load Guzzle with dependency injection ([#21](https://github.com/laravel/slack-notification-channel/pull/21))

## [v2.0.1 (2019-07-30)](https://github.com/laravel/slack-notification-channel/compare/v2.0.0...v2.0.1)

### Changed

- Return Guzzle response ([#17](https://github.com/laravel/slack-notification-channel/pull/17))
- Update version constraints for Laravel 6.0 ([5cf3064](https://github.com/laravel/slack-notification-channel/commit/5cf3064da746d18bda60a9afcb4e42dca469bcfa))

## [v2.0.0 (2019-02-26)](https://github.com/laravel/slack-notification-channel/compare/v1.0.3...v2.0.0)

### Added

- Added support for Laravel 5.8 ([f97b057](https://github.com/laravel/slack-notification-channel/commit/f97b0572a44d6c1ae72745934bc917e9ae375875))

### Changed

- Use `Facade::resolved()` before extending channel ([#9](https://github.com/laravel/slack-notification-channel/pull/9))

### Removed

- Dropped support for Laravel 5.7 ([f97b057](https://github.com/laravel/slack-notification-channel/commit/f97b0572a44d6c1ae72745934bc917e9ae375875))
