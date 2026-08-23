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
            ['key' => 'name_ar', 'display' => 'اسم التطبيق (عربي)', 'value' => 'اسم التطبيق', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'name_en', 'display' => 'اسم التطبيق (إنجليزي)', 'value' => 'App Name', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'phone', 'display' => 'رقم الهاتف', 'value' => '', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'email', 'display' => 'البريد الإلكتروني', 'value' => '', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'tax', 'display' => 'الضريبة (%)', 'value' => '0', 'type' => 'number', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'facebook', 'display' => 'فيسبوك', 'value' => '', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'twitter', 'display' => 'تويتر', 'value' => '', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'instagram', 'display' => 'انستغرام', 'value' => '', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'whatsapp', 'display' => 'واتساب', 'value' => '', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'telegram', 'display' => 'تيليجرام', 'value' => '', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'snapchat', 'display' => 'سناب شات', 'value' => '', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'tiktok', 'display' => 'تيك توك', 'value' => '', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'about_ar', 'display' => 'من نحن (عربي)', 'value' => '', 'type' => 'textarea', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'about_en', 'display' => 'من نحن (إنجليزي)', 'value' => '', 'type' => 'textarea', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'terms_ar', 'display' => 'الشروط والأحكام (عربي)', 'value' => '', 'type' => 'textarea', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'terms_en', 'display' => 'الشروط والأحكام (إنجليزي)', 'value' => '', 'type' => 'textarea', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'privacy_ar', 'display' => 'سياسة الخصوصية (عربي)', 'value' => '', 'type' => 'textarea', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'privacy_en', 'display' => 'سياسة الخصوصية (إنجليزي)', 'value' => '', 'type' => 'textarea', 'created_at' => now(), 'updated_at' => now()],

        ];

        Setting::insert($settings);
    }
}
