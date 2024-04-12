<?php

namespace MystamystInc\ModelPolicyGenerator;

use MystamystInc\ModelPolicyGenerator\Commands\InstallModelPolicyGenerator;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MystamystInc\ModelPolicyGenerator\Commands\ModelPolicyGeneratorCommand;

class ModelPolicyGeneratorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('model-policy-generator')
            ->hasConfigFile()
            ->hasCommands(
                [
                    ModelPolicyGeneratorCommand::class,
                    InstallModelPolicyGenerator::class
                ]
            );
    }
}
