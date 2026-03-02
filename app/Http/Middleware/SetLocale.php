<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah ada parameter 'lang' di URL
        if ($request->has('lang')) {
            $locale = $request->get('lang');

            // Validasi bahasa yang diizinkan
            if (in_array($locale, ['id', 'en'])) {
                Session::put('locale', $locale);
                App::setLocale($locale);
            }
        }
        // Cek apakah ada session locale
        elseif (Session::has('locale')) {
            $locale = Session::get('locale');
            App::setLocale($locale);
        }
        // Default dari config
        else {
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
