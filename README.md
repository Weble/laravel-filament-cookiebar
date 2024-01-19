# Laravel Filament CookieBar - GDPR and GTM friendly 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/weble/laravel-filament-cookiebar.svg?style=flat-square)](https://packagist.org/packages/weble/laravel-filament-cookiebar)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/weble/laravel-filament-cookiebar/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/weble/laravel-filament-cookiebar/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/weble/laravel-filament-cookiebar.svg?style=flat-square)](https://packagist.org/packages/weble/laravel-filament-cookiebar)

GDPR compliant Cookiebar integrated with Google Tag Manager Consent Mode v2 for Laravel using Filament and Livewire

## Installation

You can install the package via composer:

```bash
composer require weble/laravel-filament-cookiebar
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="cookiebar-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="cookiebar-config"
```

Optionally, you can publish the language files using

```bash
php artisan vendor:publish --tag=cookiebar-lang"
```

This is the contents of the published config file:

```php
<?php

use Weble\LaravelFilamentCookieBar\Enums\GTMConsent;

return [

    /*
     * Use this setting to enable the cookie consent dialog.
     */
    'enabled' => env('COOKIE_CONSENT_ENABLED', true),

    /*
     * The name of the cookie in which we store if the user
     * has agreed to accept the conditions.
     */
    'cookie_name' => '_cookieConsents',

    /*
     * Set the cookie duration in days.  Default is 365 days.
     */
    'cookie_lifetime' => 365,

    /**
     * Route / url to the terms page
     */
    'terms_url' => '#',

    /*
     * Consents
     * The key is the one used by GTM Consent Mode
     */
    'consent_groups' => [
        'required' => [
            'title' => 'cookiebar::consents.required.label',
            'description' => 'cookiebar::consents.required.description',
            'consents' => [
                GTMConsent::SECURITY_STORAGE,
                GTMConsent::FUNCTIONALITY_STORAGE,
            ],
            'disabled' => true,
            'default' => true,
        ],
        'analytics' => [
            'title' => 'cookiebar::consents.analytics.label',
            'description' => 'cookiebar::consents.analytics.description',
            'consents' => [
                GTMConsent::ANALYTICS_STORAGE,
                GTMConsent::PERSONALIZATION_STORAGE,
            ],
            'disabled' => false,
            'default' => false,
        ],
        'marketing' => [
            'title' => 'cookiebar::consents.marketing.label',
            'description' => 'cookiebar::consents.marketing.description',
            'consents' => [
                GTMConsent::AD_STORAGE,
                GTMConsent::AD_PERSONALIZATION,
                GTMConsent::AD_USER_DATA,
            ],
            'disabled' => false,
            'default' => false,
        ],
    ],
];

```

## Usage

This package relies and depends on the awesome [Spatie Google Tag Manager](https://github.com/spatie/laravel-googletagmanager) package.
To integrate with Google Tag Manager's consent mode v2 (which is encouraged) you should configure this package first, and then proceed into integrating the CookieBar.

### Basic Example

First you'll need to include both the default GTM Consent Mode setup and GTM scripts into your head and body tags:

```html
{{-- layout.blade.php --}}
<html>
  <head>
    @include('cookiebar::head')
    {{-- ... --}}
  </head>
  <body>
    @include('googletagmanager::body')
    {{-- ... --}}
  </body>
</html>
```

The `cookiebar::head` layout already includes spatie's GTM code for the head, so don't include both theirs script and ours.

You can then place the Cookiebar livewire component anywhere you like, and you're done,
```html
{{-- layout.blade.php --}}
<html>
  <head>
    @include('cookiebar::head')
    {{-- ... --}}
  </head>
  <body>
    @include('googletagmanager::body')
    {{-- ... --}}
    
    <livewire:cookiebar />
  </body>
</html>
```

If you are in a Livewire full page component, or prefer having more control over what happens in the livewire component itself, you can use your own Livewire component and use the `HasCookieBar` trait to include all the functionalities you need

```php
<?php

// app/Livewire/YourComponent.php
namespace App\Livewire;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Weble\LaravelFilamentCookieBar\Livewire\HasCookieBar;

class YourComponent extends Component implements HasActions, HasForms
{
    use HasCookieBar;
    use InteractsWithActions;
    use InteractsWithForms;

    public function render(): View
    {
        return view('livewire.your-component');
    }
}

```
```html
<!-- resources/views/livewire/your-component.blade.php -->
<div>
    {{ $slot ?? '' }}

    @if($showCookieBar)
        <div class="fixed bottom-0 w-full py-4 shadow-top bg-black text-white z-50">
            <div class="container mx-auto flex flex-col items-center justify-center text-center lg:text-left space-y-4">
        <span class="cookiebar__message">
           {!! __('cookiebar::banner.message', ['href' => config('cookiebar.terms_url', '#')]) !!}
        </span>

                <div class="flex flex-col items-center justify-center space-y-4">
                    <div class="flex justify-center items-center space-x-4">
                        {{ $this->showCookieModalAction }}

                        {{ $this->agreeAction }}
                    </div>

                    {{ $this->dismissAction }}
                </div>
            </div>
        </div>
    @endif

    <x-filament-actions::modals/>
</div>

```

The component will deal with creating the default consent status and sending the correct update events to GTM once the user accepts the cookies.

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
