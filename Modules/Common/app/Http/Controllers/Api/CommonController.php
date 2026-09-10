<?php

namespace Modules\Common\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    public function terms(string $lang)
    {
        return success(true, 'Terms Data  ', getSetting('terms_'.$lang));
    }

    public function privacy(string $lang)
    {
        return success(true, 'Privacy Data  ', getSetting('privacy_'.$lang));
    }

    public function about(string $lang)
    {
        return success(true, 'About Data  ', getSetting('about_'.$lang));
    }

    public function tax()
    {
        return success(true, 'Tax fetched successfully', getSetting('tax'));
    }

    public function socialLinks()
    {
        $socialLinks = getSettings(['facebook', 'twitter', 'instagram', 'whatsapp', 'telegram', 'snapchat', 'tiktok']);

        return success(true, 'Social media links fetched successfully', $socialLinks);
    }
}
