# VAS

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

```bash
composer require edlugz/vas
```

## Publish Migration Files

```bash
php artisan vendor:publish --provider="EdLugz\VAS\VASServiceProvider" --tag="migrations"
```

Fill in all the details you will be requiring for your application. Here are the env variables for quick copy paste.

```bash
VAS_API_KEY
VAS_REGISTERED_EMAIL
VAS_SENDER_ID
```

## Usage

Using the facade

Check balance
```bash
VAS::SMS()->balance();
```
Send Message
```bash
VAS::SMS()->send($mobileNumber, $message, $requestId = null);
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email eddy.lugaye@gmail.com instead of using the issue tracker.

## Credits

- [Eddy Lugaye][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/edlugz/vas.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/edlugz/vas.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/edlugz/vas/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/edlugz/vas
[link-downloads]: https://packagist.org/packages/edlugz/vas
[link-travis]: https://travis-ci.org/edlugz/vas
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/edlugz
[link-contributors]: ../../contributors
