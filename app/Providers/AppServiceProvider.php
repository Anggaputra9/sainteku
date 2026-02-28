<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Menu;

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
        view()->composer('*', function ($view) {
            $menus = Menu::whereNull('parent_id')
                ->where('is_active', 1)
                ->with('children')
                ->orderBy('order_no')
                ->get();

            $view->with('menus', $menus);
        });
    }
}
