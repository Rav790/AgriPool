<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request — set app locale from user preference or session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = 'en';

        if ($request->user() && $request->user()->language_preference) {
            $locale = $request->user()->language_preference;
        } elseif (session()->has('locale')) {
            $locale = session('locale');
        }

        if (in_array($locale, ['en', 'hi'])) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
