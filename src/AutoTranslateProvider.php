<?php

namespace Devaslanphp\AutoTranslate;

use Devaslanphp\AutoTranslate\Commands\AutoTranslate;
use Devaslanphp\AutoTranslate\Commands\TranslateMissing;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AutoTranslateProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->name('auto-translate')
            ->hasConfigFile()
            ->hasCommands([
                AutoTranslate::class,
                TranslateMissing::class
            ]);
    }

}