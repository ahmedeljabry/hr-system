<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    /**
     * Switch language in current session.
     */
    public function switch(string $locale)
    {
        if (in_array($locale, ['ar', 'en'])) {
            Session::put('locale', $locale);
            Cookie::queue(Cookie::make('locale', $locale, 60 * 24 * 30)); // 30 days
        }

        return redirect()->back();
    }
}
