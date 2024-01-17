<?php

namespace Weble\LaravelFilamentCookieBar;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Weble\LaravelFilamentCookieBar\Enums\GTMConsent;

class GTMConsentManager
{
    private ?array $savedConsentGroups;

    public function __construct()
    {
        $this->savedConsentGroups = json_decode(Cookie::get($this->cookieName()), true) ?? null;
    }

    public function cookieName(): string
    {
        return config('cookiebar.cookie_name', '_cookieAdvancedAllowed');
    }

    public function saveConsentGroups(array $consents): void
    {
        $this->savedConsentGroups = $consents;
        Cookie::queue($this->cookieName(), json_encode($consents), $this->cookieLifetime());
    }

    public function defaultConsents(): array
    {
        return collect(GTMConsent::cases())
            ->mapWithKeys(fn (GTMConsent $consent) => [$consent->value => 'denied'])
            ->all();
    }

    public function isEnabled(): bool
    {
        return config('cookiebar.enabled', true);
    }

    public function cookieLifetime(): int
    {
        return config('cookiebar.cookie_lifetime', 365) * 24 * 60;
    }

    public function currentConsents(): array
    {
        return array_merge($this->defaultConsents(), $this->savedConsents() ?? []);
    }

    public function savedConsents(): array
    {
        return collect($this->savedConsentGroups() ?? [])
            ->map(fn(string $consentGroup) => $this->consentGroups()->get($consentGroup)['consents'] ?? [])
            ->flatten()
            ->mapWithKeys(fn(GTMConsent $consent) => [$consent->value => 'granted'])
            ->all();
    }

    public function savedConsentGroups(): ?array
    {
        return $this->savedConsentGroups;
    }

    public function consentGroups(): Collection
    {
        return collect(config('cookiebar.consent_groups', []));
    }

    public function forcedConsentGroups(): Collection
    {
        return $this->consentGroups()->where('force', true);
    }

    public function isConsentGroupForced(string $consent): bool
    {
        return $this->forcedConsentGroups()->contains($consent);
    }
}
