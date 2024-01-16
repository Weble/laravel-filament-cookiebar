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
            'title' => 'cookiebar.consents.required.label',
            'description' => 'cookiebar.consents.required.description',
            'consents' => [
                GTMConsent::SECURITY_STORAGE,
                GTMConsent::FUNCTIONALITY_STORAGE,
            ],
        ],
        'analytics' => [
            'title' => 'cookiebar.consents.analytics.label',
            'description' => 'cookiebar.consents.analytics.description',
            'consents' => [
                GTMConsent::ANALYTICS_STORAGE,
                GTMConsent::FUNCTIONALITY_STORAGE,
            ],
        ],
        'marketing' => [
            'title' => 'cookiebar.consents.marketing.label',
            'description' => 'cookiebar.consents.marketing.description',
            'consents' => [
                GTMConsent::AD_STORAGE,
                GTMConsent::AD_PERSONALIZATION,
                GTMConsent::AD_USER_DATA,
            ],
        ],
    ],
];
