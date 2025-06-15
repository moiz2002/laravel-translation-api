<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['locale' => 'en', 'name' => 'English'],
            ['locale' => 'fr', 'name' => 'French'],
            ['locale' => 'es', 'name' => 'Spanish'],
            ['locale' => 'de', 'name' => 'German'],
            ['locale' => 'ar', 'name' => 'Arabic'],
            ['locale' => 'zh', 'name' => 'Chinese'],
        ];

        DB::table('languages')->insert($languages);
    }
}
