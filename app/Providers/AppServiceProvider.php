<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cartCount = 0;
            if (Auth::check()) {
                $cartCount = Cart::where('user_id', Auth::id())
                    ->whereHas('product', function ($query) {
                        $query->where('status', 'tersedia'); // Otomatis mengabaikan barang yang sudah terjual
                    })
                    ->count();
            }
            $view->with('cartCount', $cartCount);
        });
    }
}
