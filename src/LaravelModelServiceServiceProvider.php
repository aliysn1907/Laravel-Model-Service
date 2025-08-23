<?php

namespace MattYeend\LaravelModelService;

use Illuminate\Support\ServiceProvider;
use MattYeend\LaravelModelService\Commands\ModelMakeWithServiceCommand;

class LaravelModelServiceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Override the default "make:model" command
            $this->app->extend('command.model.make', function ($command, $app) {
                return new ModelMakeWithServiceCommand($app['files']);
            });

            $this->commands([
                ModelMakeWithServiceCommand::class,
            ]);
        }
    }
}
