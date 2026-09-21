<?php

namespace Modules\Common\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Common\Models\Setting;

class CommonDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'name_ar', 'display' => ['ar' => 'اسم التطبيق (عربي)', 'en' => 'App Name (Arabic)'], 'value' => null, 'type' => 'text'],
            ['key' => 'name_en', 'display' => ['ar' => 'اسم التطبيق (إنجليزي)', 'en' => 'App Name (English)'], 'value' => null, 'type' => 'text'],
            ['key' => 'phone', 'display' => ['ar' => 'رقم الهاتف', 'en' => 'Phone Number'], 'value' => null, 'type' => 'text'],
            ['key' => 'email', 'display' => ['ar' => 'البريد الإلكتروني', 'en' => 'Email Address'], 'value' => null, 'type' => 'text'],
            ['key' => 'tax', 'display' => ['ar' => 'الضريبة (%)', 'en' => 'Tax (%)'], 'value' => '0', 'type' => 'number'],
            ['key' => 'delivery_fee_per_km', 'display' => ['ar' => 'رسوم التوصيل لكل كيلومتر', 'en' => 'Delivery Fee per KM'], 'value' => '0', 'type' => 'number'],
            ['key' => 'facebook', 'display' => ['ar' => 'فيسبوك', 'en' => 'Facebook'], 'value' => null, 'type' => 'text'],
            ['key' => 'twitter', 'display' => ['ar' => 'تويتر', 'en' => 'Twitter'], 'value' => null, 'type' => 'text'],
            ['key' => 'instagram', 'display' => ['ar' => 'انستغرام', 'en' => 'Instagram'], 'value' => null, 'type' => 'text'],
            ['key' => 'whatsapp', 'display' => ['ar' => 'واتساب', 'en' => 'WhatsApp'], 'value' => null, 'type' => 'text'],
            ['key' => 'telegram', 'display' => ['ar' => 'تيليجرام', 'en' => 'Telegram'], 'value' => null, 'type' => 'text'],
            ['key' => 'snapchat', 'display' => ['ar' => 'سناب شات', 'en' => 'Snapchat'], 'value' => null, 'type' => 'text'],
            ['key' => 'tiktok', 'display' => ['ar' => 'تيك توك', 'en' => 'TikTok'], 'value' => null, 'type' => 'text'],
            ['key' => 'about_ar', 'display' => ['ar' => 'من نحن (عربي)', 'en' => 'About Us (Arabic)'], 'value' => null, 'type' => 'textarea'],
            ['key' => 'about_en', 'display' => ['ar' => 'من نحن (إنجليزي)', 'en' => 'About Us (English)'], 'value' => null, 'type' => 'textarea'],
            ['key' => 'terms_ar', 'display' => ['ar' => 'الشروط والأحكام (عربي)', 'en' => 'Terms & Conditions (Arabic)'], 'value' => null, 'type' => 'textarea'],
            ['key' => 'terms_en', 'display' => ['ar' => 'الشروط والأحكام (إنجليزي)', 'en' => 'Terms & Conditions (English)'], 'value' => null, 'type' => 'textarea'],
            ['key' => 'privacy_ar', 'display' => ['ar' => 'سياسة الخصوصية (عربي)', 'en' => 'Privacy Policy (Arabic)'], 'value' => null, 'type' => 'textarea'],
            ['key' => 'privacy_en', 'display' => ['ar' => 'سياسة الخصوصية (إنجليزي)', 'en' => 'Privacy Policy (English)'], 'value' => null, 'type' => 'textarea'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
