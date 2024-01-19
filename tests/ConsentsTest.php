<?php

use Illuminate\Cookie\CookieJar;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Weble\LaravelFilamentCookieBar\Facades\GTMConsentManager;

it('has default consents all denied', function () {
    expect(GTMConsentManager::defaultConsents())
        ->toMatchArray([
            'ad_storage' => 'denied',
            'ad_user_data' => 'denied',
            'ad_personalization' => 'denied',
            'analytics_storage' => 'denied',
            'functionality_storage' => 'denied',
            'personalization_storage' => 'denied',
            'security_storage' => 'denied',
        ]);
});

it('groups consents', function () {
    expect(GTMConsentManager::consentGroups())
        ->toHaveCount(3);
});

it('saves consent groups in a cookie', function () {
    $cookieJar = new CookieJar();
    Cookie::swap($cookieJar);

    $consents = ['marketing', 'required'];

    GTMConsentManager::saveConsentGroups($consents);

    expect($cookieJar->getQueuedCookies())->toHaveCount(1);

    $cookie = $cookieJar->getQueuedCookies()[0];
    $storedConsents = json_decode($cookie->getValue(), true);

    expect($storedConsents)->toMatchArray($consents);
});

it('respects saved consent groups ', function () {

    $request = request();
    GTMConsentManager::saveConsentGroups([
        'marketing',
    ]);

    Request::swap($request);

    expect(GTMConsentManager::currentConsents())
        ->toMatchArray([
            'ad_storage' => 'granted',
            'ad_user_data' => 'granted',
            'ad_personalization' => 'granted',
            'analytics_storage' => 'denied',
            'functionality_storage' => 'denied',
            'personalization_storage' => 'denied',
            'security_storage' => 'denied',
        ]);
});

it('render current consents', function () {

    $request = request();
    GTMConsentManager::saveConsentGroups([
        'marketing',
    ]);

    Request::swap($request);

    $json = json_encode([
        'ad_storage' => 'granted',
        'ad_user_data' => 'granted',
        'ad_personalization' => 'granted',
        'analytics_storage' => 'denied',
        'functionality_storage' => 'denied',
        'personalization_storage' => 'denied',
        'security_storage' => 'denied',
    ]);

    expect(view('cookiebar::head')->render())
        ->toContain("gtag('consent', 'default', {$json}");
});
