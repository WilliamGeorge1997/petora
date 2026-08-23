<?php

namespace Modules\Branch\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BranchSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'logo',
        'currency_ar',
        'currency_en',
        'email',
        'tax',
        'service',
        'about_ar',
        'about_en',
        'terms_ar',
        'terms_en',
        'app_background_image',
        'app_offer_image',
        'app_primary_color',
        'app_secondary_color',
        'app_indicator_color',
        'app_text_color',
        'tax_number',
        'facebook',
        'youtube',
        'instagram',
        'x',
        'snapchat',
        'tiktok',
        'whatsapp',
        'telegram',
        'theme',
        'is_order_enabled',
        'wifi_username',
        'wifi_password',
        'header_image',
        'qr_image',
        'is_qr_image_enabled',
        'is_checkout_enabled',
        'is_order_discount_enabled',
        'lang',
        'intro_message_ar',
        'intro_message_en',
        'tax_message_ar',
        'tax_message_en',
        'app_offer_product_id',
        'app_offer_ends_at',
        'app_offer_is_active',
        'send_orders_to_whatsapp',
        'google_rate_url',
        'font',
        'dark_buttons',
        'is_call_waiter_enabled'
    ];

    public function getLogoAttribute($value)
    {
        if ($value != null && $value != '') {
            return asset('uploads/branch/logo/' . $value);
        }
        return $value;
    }

    public function getAppBackgroundImageAttribute($value)
    {
        if ($value != null && $value != '') {
            return asset('uploads/branch/setting/' . $value);
        }
        return $value;
    }


    public function getAppOfferImageAttribute($value)
    {
        if ($value != null && $value != '') {
            return asset('uploads/branch/setting/' . $value);
        }
        return $value;
    }

    public function getHeaderImageAttribute($value)
    {
        if ($value != null && $value != '') {
            return asset('uploads/branch/setting/' . $value);
        }
        return $value;
    }

    public function getQrImageAttribute($value)
    {
        if ($value != null && $value != '') {
            return asset('uploads/branch/setting/' . $value);
        }
        return $value;
    }
}
