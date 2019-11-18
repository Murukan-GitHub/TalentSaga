<?php

namespace Suitcore\Emailer;

use Illuminate\Support\ServiceProvider;

class EmailerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('emailer', function ($app) {
            $mailer = $this->app['mailer'];
            return new Emailer($mailer);
        });
    }

    public static function shouldRegistered()
    {
        return (new static(app()))->register();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['emailer'];
    }
}
