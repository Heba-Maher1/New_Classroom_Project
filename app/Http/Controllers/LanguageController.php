<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    public function changeLanguage($locale)
    {
        $supported = ['en', 'ar'];

        if (in_array($locale, $supported)) {
            session(['locale' => $locale]);

            $user = Auth::user();

            if ($user) {
                $user->profile->locale = $locale;
                $user->profile->save();
            }
        }

        return redirect()->back();
    }
}
