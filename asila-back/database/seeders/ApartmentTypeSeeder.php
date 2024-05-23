<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ApartmentType;
use App\Models\ApartmentTypeTranslation;
use App\Models\Locale;

class ApartmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apartmentTypes = [
            'Home' => [
                'en' => 'Home',
                'ar' => 'شقة',
                'tr' => 'apartman',
            ],
            'Villa' => [
                'en' => 'Villa',
                'ar' => 'فيلا',
                'tr' => 'villa',
            ],
            'Hotel Apartment' => [
                'en' => 'Hotel Apartment',
                'ar' => 'شقة فندقية',
                'tr' => 'otel dairesi',
            ],
        ];

        foreach ($apartmentTypes as $typeName => $translations) {
            $apartmentType = ApartmentType::create();

            foreach ($translations as $localeCode => $typeTranslation) {
                $locale = Locale::where('code', $localeCode)->first();

                ApartmentTypeTranslation::create([
                    'apartment_type_id' => $apartmentType->id,
                    'locale_id' => $locale->id,
                    'type' => $typeTranslation,
                ]);
            }
        }
    }
}
