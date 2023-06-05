<?php

namespace App\Providers;
use App\Models\Regra;
use App\Models\Taxas;
use App\Observers\RegraObserver;
use App\Observers\TaxaObserver;
use Barryvdh\Debugbar\Facades\Debugbar;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Debugbar::enable();
        Regra::observe(RegraObserver::class);
        Taxas::observe(TaxaObserver::class);
    }
}
