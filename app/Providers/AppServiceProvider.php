<?php

namespace App\Providers;

use App\Models\BookRequest;
use Illuminate\Support\Facades\View;

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

    public function boot()
    {
        View::composer('*', function ($view) {
            $pendingRequestCount = BookRequest::where('status', 'pending')->count();
            $view->with('pendingRequestCount', $pendingRequestCount);
        });
    }
}
