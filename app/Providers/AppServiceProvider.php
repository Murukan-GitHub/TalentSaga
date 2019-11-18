<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Suitcore\Emailer\EmailerServiceProvider;
use Suitcore\Uploader\Upload;
use Suitcore\Thumbnailer\Thumbnailer;
use Suitcore\FileGrabber\FileGrabber;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('upload', function () {
            return new Upload;
        });

        $this->app->singleton('thumbnailer', function () {
            return new Thumbnailer;
        });

        $this->app->singleton('filegrab', function ($app) {
            $grabber = new FileGrabber;
            $grabber->setTemp(storage_path('app/grabbers'), 0775);
            return $grabber;
        });
    }
}
