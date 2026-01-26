<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Change application locale and redirect back.
     */
    public function switch(Request $request, $locale)
    {
        $available = ['en', 'vi'];
        if (!in_array($locale, $available)) {
            abort(404);
        }

        session(['app_locale' => $locale]);

        $back = $request->input('redirect_to') ?? url()->previous();

        return redirect($back);
    }
}
