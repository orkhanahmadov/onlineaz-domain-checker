<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\HTMLParser\OnlineazProvider;
use App\Services\HTMLParser\Contracts\Provider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->instance(Provider::class, new OnlineazProvider);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
