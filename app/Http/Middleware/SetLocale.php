<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request and apply requested locale from session.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale', $request->cookie('locale', config('app.locale')));

        if (in_array($locale, ['ar', 'en'])) {
            App::setLocale($locale);
            
            // Sync cookie back to session if it wasn't there
            if (!Session::has('locale')) {
                Session::put('locale', $locale);
            }
        }

        return $next($request);
    }
}
