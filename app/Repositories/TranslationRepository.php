<?php

namespace App\Repositories;

use App\Models\Translation;
use App\Models\TranslationEntry;
use App\Models\Language;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class TranslationRepository implements TranslationRepositoryInterface
{
    public function updateOrCreateTranslation(string $key, ?string $description): int
    {
        return Translation::updateOrCreate(
            ['key' => $key],
            ['description' => $description]
        )->id;
    }

    public function updateOrCreateEntry(
        int $translationId,
        string $locale,
        string $tag,
        string $content
    ): void {
        $language = Language::firstOrCreate(['locale' => $locale]);
        $tagModel = Tag::firstOrCreate(['name' => $tag]);

        TranslationEntry::updateOrCreate(
            [
                'translation_id' => $translationId,
                'language_id'    => $language->id,
                'tag_id'         => $tagModel->id
            ],
            [
                'locale'  => $locale,
                'content' => $content
            ]
        );
    }

public function getFilteredTranslations(array $filters): array
{
    $query = DB::table('translations')
        ->select(
            'translations.id as translation_id',
            'translations.key',
            'translations.description',
            'translation_entries.content',
            'languages.locale',
            'tags.name as tag'
        )
        ->join('translation_entries', 'translation_entries.translation_id', '=', 'translations.id')
        ->join('languages', 'languages.id', '=', 'translation_entries.language_id')
        ->join('tags', 'tags.id', '=', 'translation_entries.tag_id');

    if (!empty($filters['key'])) {
        $query->where('translations.key', '=', $filters['key']);
    }

    if (!empty($filters['content'])) {
        $query->where('translation_entries.content', 'like', '%' . $filters['content'] . '%');
    }

    if (!empty($filters['locale'])) {
        $query->where('languages.locale', '=', $filters['locale']);
    }

    if (!empty($filters['tag'])) {
        $query->where('tags.name', '=', $filters['tag']);
    }

    return $query->limit(100)->get()->toArray();
}

public function getExportedJson(string $locale, ?string $tag = null): string
{
    // Fetch language_id once for the given locale
    $languageId = DB::table('languages')
        ->where('locale', $locale)
        ->value('id');

    if (!$languageId) {
        return json_encode([]); // or throw exception
    }

    $query = DB::table('translation_entries')
        ->join('translations', 'translation_entries.translation_id', '=', 'translations.id')
        ->select('translations.key', 'translation_entries.content')
        ->where('translation_entries.language_id', $languageId);

    if ($tag) {
        $query->join('tags', 'translation_entries.tag_id', '=', 'tags.id')
              ->where('tags.name', $tag);
    }

    $result = $query->get()->pluck('content', 'key');

    return json_encode($result, JSON_UNESCAPED_UNICODE);
}

}
