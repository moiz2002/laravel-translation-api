<?php
namespace App\Services;
use App\Repositories\TranslationRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TranslationService
{
    public function __construct(
        protected TranslationRepositoryInterface $translationRepository
    ) {}

    public function createOrUpdateTranslation(array $data): void
    {
        DB::transaction(function () use ($data) {
            $translationId = $this->translationRepository->updateOrCreateTranslation(
                $data['key'],
                $data['description'] ?? null
            );

            foreach ($data['entries'] as $entry) {
                $this->translationRepository->updateOrCreateEntry(
                    $translationId,
                    $entry['locale'],
                    $entry['tag'],
                    $entry['content']
                );
            }
        });
    }

    public function searchTranslations(array $filters): array
    {
        return $this->translationRepository->getFilteredTranslations($filters);
    }

    public function exportAsJson(string $locale, ?string $tag = null): string
    {
        return $this->translationRepository->getExportedJson($locale, $tag);
    }
}

