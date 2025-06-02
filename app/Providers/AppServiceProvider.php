<?php

namespace App\Providers;

use App\Models\Amendement;
use App\Models\Document;
use App\Observers\AmendementObserver;
use App\Observers\DocumentObserver;
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
        Document::observe(DocumentObserver::class);
        Amendement::observe(AmendementObserver::class);
    }
}
