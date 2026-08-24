<?php

namespace Modules\Common\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    /**
     * Swap the application locale.
     *
     * @param string $locale
     * @return RedirectResponse
     */
    public function __invoke(string $locale): RedirectResponse
    {
        session()->put('locale', $locale);
        
        if (auth('admin')->check()) {
            /** @var \Modules\Admin\Models\Admin $user */
            $user = auth('admin')->user();
            $user->update(['locale' => $locale]);
        }

        return redirect()->back();
    }
}
