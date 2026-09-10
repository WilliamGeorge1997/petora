<?php

namespace Modules\Country\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Country\Models\City;
use Modules\Country\Models\Country;
use Modules\Country\Models\Zone;

class CountryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $country = $this->createCountry();
        if ($country) {
            $this->createCities($country);
            $this->createZones();
        }
    }

    private function createCountry()
    {
        return Country::firstOrCreate(
            ['id' => 1],
            [
                'title' => ['en' => 'Egypt', 'ar' => 'مصر'],
                'is_active' => true,
            ]
        );
    }

    private function createCities($country)
    {
        $cities = [
            ['en' => 'Cairo', 'ar' => 'القاهرة'],
            ['en' => 'Alexandria', 'ar' => 'الإسكندرية'],
            ['en' => 'Giza', 'ar' => 'الجيزة'],
            ['en' => 'Luxor', 'ar' => 'الأقصر'],
            ['en' => 'Aswan', 'ar' => 'أسوان'],
            ['en' => 'Port Said', 'ar' => 'بورسعيد'],
            ['en' => 'Suez', 'ar' => 'السويس'],
            ['en' => 'Mansoura', 'ar' => 'المنصورة'],
            ['en' => 'Tanta', 'ar' => 'طنطا'],
            ['en' => 'Asyut', 'ar' => 'أسيوط'],
            ['en' => 'Ismailia', 'ar' => 'الإسماعيلية'],
            ['en' => 'Fayoum', 'ar' => 'الفيوم'],
            ['en' => 'Zagazig', 'ar' => 'الزقازيق'],
            ['en' => 'Damietta', 'ar' => 'دمياط'],
            ['en' => 'Minya', 'ar' => 'المنيا'],
            ['en' => 'Beni Suef', 'ar' => 'بني سويف'],
            ['en' => 'Hurghada', 'ar' => 'الغردقة'],
            ['en' => 'Qena', 'ar' => 'قنا'],
            ['en' => 'Sohag', 'ar' => 'سوهاج'],
            ['en' => 'Shibin El Kom', 'ar' => 'شبين الكوم'],
            ['en' => 'Banha', 'ar' => 'بنها'],
            ['en' => 'Kafr El Sheikh', 'ar' => 'كفر الشيخ'],
            ['en' => 'Arish', 'ar' => 'العريش'],
            ['en' => 'Damanhur', 'ar' => 'دمنهور'],
            ['en' => 'Sharm El Sheikh', 'ar' => 'شرم الشيخ'],
        ];

        foreach ($cities as $cityTitle) {
            City::firstOrCreate(
                ['title->en' => $cityTitle['en']],
                [
                    'country_id' => $country->id,
                    'title' => $cityTitle,
                    'is_active' => true,
                ]
            );
        }
    }

    private function createZones()
    {
        // Zones for Cairo
        $cairo = City::where('title->en', 'Cairo')->first();
        if ($cairo) {
            $cairoZones = [
                ['en' => 'Nasr City', 'ar' => 'مدينة نصر'],
                ['en' => 'Maadi', 'ar' => 'المعادي'],
                ['en' => 'Heliopolis', 'ar' => 'مصر الجديدة'],
                ['en' => 'New Cairo', 'ar' => 'القاهرة الجديدة'],
                ['en' => 'Zamalek', 'ar' => 'الزمالك'],
                ['en' => 'Downtown', 'ar' => 'وسط البلد'],
            ];
            foreach ($cairoZones as $zoneTitle) {
                Zone::firstOrCreate(
                    ['title->en' => $zoneTitle['en']],
                    [
                        'city_id' => $cairo->id,
                        'title' => $zoneTitle,
                        'is_active' => true,
                    ]
                );
            }
        }

        // Zones for Alexandria
        $alex = City::where('title->en', 'Alexandria')->first();
        if ($alex) {
            $alexZones = [
                ['en' => 'Smouha', 'ar' => 'سموحة'],
                ['en' => 'Sidi Beshr', 'ar' => 'سيدي بشر'],
                ['en' => 'Gleem', 'ar' => 'جليم'],
                ['en' => 'Stanley', 'ar' => 'ستانلي'],
                ['en' => 'Roushdy', 'ar' => 'رشدي'],
                ['en' => 'Agami', 'ar' => 'العجمي'],
            ];
            foreach ($alexZones as $zoneTitle) {
                Zone::firstOrCreate(
                    ['title->en' => $zoneTitle['en']],
                    [
                        'city_id' => $alex->id,
                        'title' => $zoneTitle,
                        'is_active' => true,
                    ]
                );
            }
        }

        // Zones for Giza
        $giza = City::where('title->en', 'Giza')->first();
        if ($giza) {
            $gizaZones = [
                ['en' => 'Dokki', 'ar' => 'الدقي'],
                ['en' => 'Mohandeseen', 'ar' => 'المهندسين'],
                ['en' => 'Agouza', 'ar' => 'العجوزة'],
                ['en' => '6th of October', 'ar' => 'السادس من أكتوبر'],
                ['en' => 'Sheikh Zayed', 'ar' => 'الشيخ زايد'],
                ['en' => 'Haram', 'ar' => 'الهرم'],
            ];
            foreach ($gizaZones as $zoneTitle) {
                Zone::firstOrCreate(
                    ['title->en' => $zoneTitle['en']],
                    [
                        'city_id' => $giza->id,
                        'title' => $zoneTitle,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
