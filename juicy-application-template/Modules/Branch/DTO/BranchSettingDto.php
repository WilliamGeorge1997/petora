<?php

namespace Modules\Branch\DTO;

class BranchSettingDto
{
    public $currency_ar;
    public $currency_en;
    public $email;
    public $tax;
    public $service;
    public $about_ar;
    public $dark_buttons;
    public $about_en;
    public $terms_ar;
    public $terms_en;
    public $app_primary_color;
    public $app_secondary_color;
    public $app_indicator_color;
    public $app_text_color;
    public $tax_number;
    public $facebook;
    public $youtube;
    public $instagram;
    public $x;
    public $snapchat;
    public $tiktok;
    public $whatsapp;
    public $telegram;
    public $wifi_username;
    public $wifi_password;
    public $is_order_enabled;
    public $is_checkout_enabled;
    public $is_qr_image_enabled;
    public $lang;
    public $intro_message_ar;
    public $intro_message_en;
    public $tax_message_ar;
    public $tax_message_en;
    public $send_orders_to_whatsapp;
    public $google_rate_url;
    public $font;
    public $is_call_waiter_enabled;

    public function __construct($request)
    {
        $this->currency_ar = $request->get('currency_ar');
        $this->currency_en = $request->get('currency_en');
        $this->email = $request->get('email');
        $this->tax = $request->get('tax');
        $this->service = $request->get('service');
        $this->terms_ar = $request->get('terms_ar');
        $this->terms_en = $request->get('terms_en');
        $this->about_ar = $request->get('about_ar');
        $this->about_en = $request->get('about_en');
        $this->app_primary_color = $request->get('app_primary_color');
        $this->app_secondary_color = $request->get('app_secondary_color');
        $this->app_indicator_color = $request->get('app_indicator_color');
        $this->app_text_color = $request->get('app_text_color');
        $this->tax_number = $request->get('tax_number');
        $this->facebook = $request->get('facebook');
        $this->youtube = $request->get('youtube');
        $this->instagram = $request->get('instagram');
        $this->x = $request->get('x');
        $this->snapchat = $request->get('snapchat');
        $this->tiktok = $request->get('tiktok');
        $this->whatsapp = $request->get('whatsapp');
        $this->telegram = $request->get('telegram');
        $this->wifi_username = $request->get('wifi_username');
        $this->wifi_password = $request->get('wifi_password');
        $this->is_order_enabled = isset($request['is_order_enabled']) ? 1 : 0;
        $this->is_checkout_enabled = isset($request['is_checkout_enabled']) ? 1 : 0;
        $this->is_qr_image_enabled = isset($request['is_qr_image_enabled']) ? 1 : 0;
        $this->send_orders_to_whatsapp = isset($request['send_orders_to_whatsapp']) ? 1 : 0;
        $this->lang = $request->get('lang');
        $this->intro_message_ar = $request->get('intro_message_ar');
        $this->intro_message_en = $request->get('intro_message_en');
        $this->tax_message_ar = $request->get('tax_message_ar');
        $this->tax_message_en = $request->get('tax_message_en');
        $this->google_rate_url = $request->get('google_rate_url');
        $this->font = $request->get('font');
        $this->dark_buttons = isset($request['dark_buttons']) ? 1 : 0;
        $this->is_call_waiter_enabled = isset($request['is_call_waiter_enabled']) ? 1 : 0;
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($data['currency_ar'] == null) unset($data['currency_ar']);
        if ($data['currency_en'] == null) unset($data['currency_en']);
        return $data;
    }
}
