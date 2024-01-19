<?php

namespace Weble\LaravelFilamentCookieBar\Livewire;

use Filament\Actions\Action;
use Filament\Forms\Components\Toggle;
use Weble\LaravelFilamentCookieBar\Facades\GTMConsentManager;

trait HasCookieBar
{
    public bool $showCookieBar = true;

    public function mountHasCookieBar(): void
    {
        $this->showCookieBar = GTMConsentManager::isEnabled() && ! GTMConsentManager::savedConsentGroups();
    }

    public function showCookieModalAction(?string $label = null): Action
    {
        return Action::make('showCookieModal')
            ->label($label ?? __('cookiebar::banner.manage'))
            ->form(
                GTMConsentManager::consentGroups()
                    ->map(
                        fn (array $consentOptions, string $key): Toggle => Toggle::make($key)
                            ->label(__($consentOptions['title'] ?? $key))
                            ->helperText(__($consentOptions['description'] ?? null))
                            ->disabled($consentOptions['disabled'] ?? false)
                            ->accepted($consentOptions['default'] ?? false)
                    )
                    ->all()
            )
            ->fillForm(
                GTMConsentManager::consentGroups()
                    ->mapWithKeys(fn (array $consentOptions, string $consent): array => [
                        $consent => $consentOptions['default'] ? 1 : 0,
                    ])
                    ->all()
            )
            ->modalHeading(__('cookiebar::modal.heading'))
            ->modalDescription(__('cookiebar::modal.description'))
            ->closeModalByClickingAway(false)
            ->modalCloseButton(false)
            ->action(fn (array $data) => $this->saveCookieSettings($data));
    }

    public function dismissAction(): Action
    {
        return Action::make('dismiss')
            ->label(__('cookiebar::banner.dismiss'))
            ->link()
            ->size('xs')
            ->action(function () {
                $this->saveCookieSettings(
                    GTMConsentManager::consentGroups()
                        ->map(fn () => false)
                        ->all()
                );
                $this->showCookieBar = false;
            });
    }

    public function agreeAction(): Action
    {
        return Action::make('agree')
            ->label(__('cookiebar::banner.agree'))
            ->color('primary')
            ->action(function () {
                $this->saveCookieSettings(
                    GTMConsentManager::consentGroups()
                        ->map(fn () => true)
                        ->all()
                );
                $this->showCookieBar = false;
            });
    }

    private function sendGTMConsentUpdate(array $consents): void
    {
        $jsConsents = json_encode($consents);

        $this->js(
            <<<JS
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
