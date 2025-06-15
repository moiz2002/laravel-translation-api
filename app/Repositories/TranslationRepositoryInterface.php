<?php

namespace App\Repositories;

interface TranslationRepositoryInterface
{
    public function updateOrCreateTranslation(string $key, ?string $description): int;

    public function updateOrCreateEntry(
        int $translationId,
        string $locale,
        string $tag,
        string $content
    ): void;

    public function getFilteredTranslations(array $filters): array;

    public function getExportedJson(string $locale, ?string $tag = null): string;
}
