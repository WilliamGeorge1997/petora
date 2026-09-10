<?php

namespace Modules\Common\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Admin\Models\Admin;

class LanguageController extends Controller
{
    /**
     * Swap the application locale.
     */
    public function __invoke(string $locale): RedirectResponse
    {
        session()->put('locale', $locale);

        if (auth('admin')->check()) {
            /** @var Admin $user */
            $user = auth('admin')->user();
            $user->update(['locale' => $locale]);
        }

        return redirect()->back();
    }
}
