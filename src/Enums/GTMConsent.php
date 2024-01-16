<?php

namespace Weble\LaravelFilamentCookieBar\Enums;

enum GTMConsent: string
{
    case AD_STORAGE = 'ad_storage';
    case AD_USER_DATA = 'ad_user_data';
    case AD_PERSONALIZATION = 'ad_personalization';
    case ANALYTICS_STORAGE = 'analytics_storage';
    case FUNCTIONALITY_STORAGE = 'functionality_storage';
    case PERSONALIZATION_STORAGE = 'personalization_storage';
    case SECURITY_STORAGE = 'security_storage';
}
