<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\TranslationService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslationController extends Controller
{
    public function __construct(
        protected TranslationService $translationService
    ) {}

    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255',
            'description' => 'nullable|string',
            'entries' => 'required|array',
            'entries.*.locale' => 'required|string|max:10',
            'entries.*.tag' => 'required|string|max:50',
            'entries.*.content' => 'required|string',
        ]);

        $this->translationService->createOrUpdateTranslation($validated);

        return response()->json(['message' => 'Translation saved'], 201);
    }

    public function index(Request $request): Response
    {
        $filters = $request->only(['tag', 'key','locale', 'content']);
        $translations = $this->translationService->searchTranslations($filters);

        return response()->json($translations);
    }

    public function exportJson(Request $request): Response
    {
        $validated = $request->validate([
            'locale' => 'required|string|max:10',
            'tag' => 'nullable|string|max:50',
        ]);

        $data = $this->translationService->exportAsJson($validated['locale'], $validated['tag'] ?? null);

        return response($data, 200)->header('Content-Type', 'application/json');
    }
}
