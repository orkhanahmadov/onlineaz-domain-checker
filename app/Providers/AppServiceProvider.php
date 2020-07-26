<?php

namespace App\Providers;

use App\Services\HTMLParser\Contracts\Provider;
use App\Services\HTMLParser\OnlineazProvider;
use Illuminate\Support\ServiceProvider;

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
