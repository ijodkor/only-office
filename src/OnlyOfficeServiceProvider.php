<?php

namespace OnlyOffice;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class OnlyOfficeServiceProvider extends PackageServiceProvider {

    private const APP_NAME = "only-office";
    private const APP_NAME_ALIAS = "office";

    public function boot(): void {
        parent::boot();
        $this->assetsPublish();
    }

    public function configurePackage(Package $package): void {
        $package->name(self::APP_NAME)
            ->hasConfigFile(self::APP_NAME_ALIAS)
            ->hasRoutes('web', 'api')
            ->hasViews(self::APP_NAME_ALIAS);
    }

    private function assetsPublish(): void {
        // Publish css and js files
        $this->publishes([
            __DIR__ . '/../assets' => public_path('vendor/office/assets'),
        ], 'assets');

        // Publish example files
        $this->publishes([
            __DIR__ . '/../examples' => storage_path('app/private/examples'),
        ], 'assets');
    }
}
