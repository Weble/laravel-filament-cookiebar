<?php

use Illuminate\Cookie\CookieJar;
use Illuminate\Support\Facades\Cookie;
use Livewire\Livewire;
use Weble\LaravelFilamentCookieBar\Livewire\CookieBar;

it('renders a cookiebar', function () {
    $component = Livewire::test(CookieBar::class)
        ->assertOk();

    expect($component->get('showCookieBar'))->toBe(true);

    $component->assertSee(__('cookiebar::banner.message', ['href' => config('cookiebar.terms_url', '#')]));
});

it('opens the modal', function () {
    $component = Livewire::test(CookieBar::class);
    $component
        ->mountAction('showCookieModal')
        ->assertFormExists()
        ->assertSee(__('cookiebar::modal.heading'));
});

it('saves the selected consents', function () {

    $cookieJar = new CookieJar();
    Cookie::swap($cookieJar);

    $component = Livewire::test(CookieBar::class);
    $component
        ->mountAction('showCookieModal')
        ->callAction('showCookieModal', [
            'marketing' => 1,
        ]);

    $component->assertHasNoActionErrors();

    expect($cookieJar->getQueuedCookies())->toHaveCount(1);

    $cookie = $cookieJar->getQueuedCookies()[0];
    $storedConsents = json_decode($cookie->getValue(), true);

    expect($storedConsents)->toMatchArray([
        'marketing' => 1,
        'analytics' => false,
    ]);
});
