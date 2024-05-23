<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locales = [
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'ar', 'name' => 'Arabic'],
            ['code' => 'tr', 'name' => 'Turkish'],
            // Add more locales if needed
        ];

        DB::table('locales')->insert($locales);
    }
}
