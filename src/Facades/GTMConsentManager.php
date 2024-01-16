<?php

namespace Weble\LaravelFilamentCookieBar\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Weble\LaravelFilamentCookieBar\GTMConsentManager
 */
class GTMConsentManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Weble\LaravelFilamentCookieBar\GTMConsentManager::class;
    }
}
