<?php

namespace Vluzrmos\LocaleDetector;

use Illuminate\Support\ServiceProvider;

class LocaleDetectorServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }


    public function register()
    {
        $detector = new LocaleDetector($this->app['request'], $this->app['translator'], $this->app['path.lang']);

        $detector->detect();
    }
}
