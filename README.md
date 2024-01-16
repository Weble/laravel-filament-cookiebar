# GDPR compliant Cookiebar integrated with Google Tag Manager Consent Mode v2 for Laravel using Filament and Livewire

[![Latest Version on Packagist](https://img.shields.io/packagist/v/weble/laravel-filament-cookiebar.svg?style=flat-square)](https://packagist.org/packages/weble/laravel-filament-cookiebar)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/weble/laravel-filament-cookiebar/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/weble/laravel-filament-cookiebar/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/weble/laravel-filament-cookiebar/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/weble/laravel-filament-cookiebar/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/weble/laravel-filament-cookiebar.svg?style=flat-square)](https://packagist.org/packages/weble/laravel-filament-cookiebar)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require weble/laravel-filament-cookiebar
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-filament-cookiebar-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-filament-cookiebar-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-filament-cookiebar-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$laravelFilamentCookiebar = new Weble\LaravelFilamentCookieBar();
echo $laravelFilamentCookiebar->echoPhrase('Hello, Weble!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Daniele Rosario](https://github.com/Skullbock)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
