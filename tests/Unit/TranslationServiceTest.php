<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TranslationService;
use App\Repositories\TranslationRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class TranslationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_or_updates_translation()
    {
        $repo = Mockery::mock(TranslationRepositoryInterface::class);
        $service = new TranslationService($repo);

        $data = [
            'key' => 'home.title',
            'description' => 'Homepage title',
            'entries' => [
                ['locale' => 'en', 'tag' => 'web', 'content' => 'Welcome'],
                ['locale' => 'fr', 'tag' => 'web', 'content' => 'Bienvenue'],
            ]
        ];

        $repo->shouldReceive('updateOrCreateTranslation')
            ->once()
            ->andReturn(1);

        $repo->shouldReceive('updateOrCreateEntry')
            ->twice();

        $service->createOrUpdateTranslation($data);

        $this->assertTrue(true); // No exception thrown
    }

    public function test_it_searches_translations()
    {
        $repo = Mockery::mock(TranslationRepositoryInterface::class);
        $service = new TranslationService($repo);

        $repo->shouldReceive('getFilteredTranslations')
            ->with(['key' => 'home'])
            ->once()
            ->andReturn([['key' => 'home.title']]);

        $results = $service->searchTranslations(['key' => 'home']);
        $this->assertEquals('home.title', $results[0]['key']);
    }

    public function test_it_exports_json()
    {
        $repo = Mockery::mock(TranslationRepositoryInterface::class);
        $service = new TranslationService($repo);

        $expectedJson = json_encode(['home.title' => 'Welcome']);
        $repo->shouldReceive('getExportedJson')
            ->with('en', null)
            ->once()
            ->andReturn($expectedJson);

        $actual = $service->exportAsJson('en');
        $this->assertEquals($expectedJson, $actual);
    }
}
