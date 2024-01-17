<?php

namespace Weble\LaravelFilamentCookieBar\Livewire;

use Filament\Actions\Action;
use Filament\Forms\Components\Toggle;
use Weble\LaravelFilamentCookieBar\Enums\GTMConsent;
use Weble\LaravelFilamentCookieBar\Facades\GTMConsentManager;

trait HasCookieBar
{
    public bool $showCookieBar = true;

    public function mountHasCookieBar(): void
    {
        $this->showCookieBar = GTMConsentManager::isEnabled() && !GTMConsentManager::savedConsentGroups();
    }

    public function showCookieModalAction(): Action
    {
        return Action::make('showCookieModal')
            ->label(__('cookiebar::cookiebar.banner.manage'))
            ->form(
                GTMConsentManager::consentGroups()
                    ->map(fn(array $consentOptions, string $key): Toggle => Toggle::make($key)
                        ->label(__($consentOptions['label'] ?? $key))
                        ->helperText(__($consentOptions['description'] ?? null))
                        ->disabled(GTMConsentManager::isConsentGroupForced($key))
                        ->accepted(GTMConsentManager::isConsentGroupForced($key))
                    )
                    ->all()
            )
            ->fillForm(
                GTMConsentManager::forcedConsentGroups()
                    ->mapWithKeys(fn(string $consent): array => [
                        $consent => 1
                    ])
                    ->all()
            )
            ->modalHeading(__('cookiebar::cookiebar.modal.heading'))
            ->modalDescription(__('cookiebar::cookiebar.modal.description'))
            ->closeModalByClickingAway(false)
            ->modalCloseButton(false)
            ->action(fn(array $data) => $this->saveCookieSettings($data));
    }

    public function dismissAction(): Action
    {
        return Action::make('dismiss')
            ->label(__('cookiebar::cookiebar.banner.dismiss'))
            ->link()
            ->size('xs')
            ->action(function () {
                $this->saveCookieSettings();
                $this->showCookieBar = false;
            });
    }

    public function agreeAction(): Action
    {
        return Action::make('agree')
            ->label(__('cookiebar::cookiebar.banner.agree'))
            ->color('primary')
            ->action(function () {
                $this->saveCookieSettings(config('cookiebar.gtag_consents', []));
                $this->showCookieBar = false;
            });
    }

    private function sendGTMConsentUpdate(array $consents): void
    {
        $jsConsents = json_encode($consents);

        $this->js(<<<JS
            gtag('consent', 'update', $jsConsents )
        JS
        );
    }

    private function saveCookieSettings(array $data): void
    {
        GTMConsentManager::saveConsentGroups($data);

        $this->sendGTMConsentUpdate(GTMConsentManager::savedConsents());
    }
}
