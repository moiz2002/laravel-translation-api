<?php

namespace Database\Seeders;

use App\Models\Translation;
use App\Models\TranslationEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LargeTranslationSeeder extends Seeder
{
    public function run(): void
    {
        $languages = DB::table('languages')
            ->whereIn('locale', ['en', 'fr', 'es', 'de', 'ar', 'zh'])
            ->get(['id', 'locale']);

        $tags = DB::table('tags')
            ->whereIn('name', ['web', 'mobile', 'desktop'])
            ->pluck('id')
            ->all();

        $batchSize = 500;
        $total = 25000; // 25k translations × 4+ locales ≈ 100k entries
        $keyCounter = 1;

        for ($i = 0; $i < $total; $i += $batchSize) {
            $translations = [];

            // Manually create unique keys
            for ($j = 0; $j < $batchSize; $j++) {
                $translations[] = [
                    'key' => "translation_key_" . $keyCounter++,
                    'description' => fake()->sentence(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Bulk insert translations
            Translation::insert($translations);

            // Fetch inserted translations
            $inserted = Translation::latest('id')->take($batchSize)->get();

            $entries = [];

            foreach ($inserted as $translation) {
                $selectedLangs = $languages->shuffle()->take(rand(3, 6));

                foreach ($selectedLangs as $lang) {
                    $entries[] = [
                        'translation_id' => $translation->id,
                        'language_id' => $lang->id,
                        'locale' => $lang->locale,
                        'tag_id' => $tags[array_rand($tags)],
                        'content' => fake()->sentence(3),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            TranslationEntry::insert($entries);
            echo ".";
        }

        echo "\n Done seeding 100k+ translation entries with safe unique keys and locales.\n";
    }
}
