<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(string $locale)
    {
        if (!in_array($locale, ['en', 'hi'])) {
            abort(400);
        }

        session(['locale' => $locale]);

        if (auth()->check()) {
            auth()->user()->update(['language_preference' => $locale]);
        }

        return redirect()->back();
    }
}
