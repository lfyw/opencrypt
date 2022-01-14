<?php

namespace Lfyw\Opencrypt;

use Lfyw\Opencrypt\Commands\OpencryptKeyGenerateCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(OpencryptApplication::class, function () {
            return new OpencryptApplication();
        });

        $this->app->alias(OpencryptApplication::class, 'opencrypt');

        $this->mergeConfigFrom(
            __DIR__.'/../config/opencrypt.php', 'opencrypt'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/opencrypt.php' => config_path('opencrypt.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                OpencryptKeyGenerateCommand::class,
            ]);
        }
    }

    public function provides()
    {
        return [OpencryptApplication::class, 'opencrypt'];
    }
}
