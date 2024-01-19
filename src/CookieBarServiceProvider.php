<?php

namespace Weble\LaravelFilamentCookieBar;

use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\View\View;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Weble\LaravelFilamentCookieBar\Livewire\CookieBar;
use Weble\LaravelFilamentCookieBar\Testing\TestsCookieBar;

class CookieBarServiceProvider extends PackageServiceProvider
{
    public static string $name = 'cookiebar';

    public static string $viewNamespace = 'cookiebar';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('weble/laravel-filament-cookiebar');
            })
            ->hasTranslations()
            ->hasViewComposer('cookiebar::head', fn (View $view) => $view->with([
                'consents' => \Weble\LaravelFilamentCookieBar\Facades\GTMConsentManager::currentConsents(),
            ]));

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/laravel-filament-cookiebar/{$file->getFilename()}"),
                ], 'laravel-filament-cookiebar-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsCookieBar());

        Livewire::component('cookiebar', CookieBar::class);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'weble/laravel-filament-cookiebar';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('laravel-filament-cookiebar', __DIR__ . '/../resources/dist/components/laravel-filament-cookiebar.js'),
            Css::make('laravel-filament-cookiebar-styles', __DIR__ . '/../resources/dist/laravel-filament-cookiebar.css'),
            Js::make('laravel-filament-cookiebar-scripts', __DIR__ . '/../resources/dist/laravel-filament-cookiebar.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [];
    }
}
