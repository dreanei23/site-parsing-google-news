<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Parser;

class FacadeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('parser', fn () => new Parser());
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
