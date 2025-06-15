<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Translation;
use App\Models\Language;
use App\Models\Tag;
use App\Models\TranslationEntry;

class TranslationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_translation()
    {
        $response = $this->postJson('/api/translations', [
            'key' => 'home.title',
            'description' => 'Homepage Title',
            'entries' => [
                ['locale' => 'en', 'tag' => 'web', 'content' => 'Welcome'],
                ['locale' => 'fr', 'tag' => 'web', 'content' => 'Bienvenue'],
            ]
        ]);

        $response->assertStatus(201)
                 ->assertJson(['message' => 'Translation saved']);

        $this->assertDatabaseHas('translations', ['key' => 'home.title']);
    }

    public function test_can_search_translations()
    {
        $language = Language::create(['locale' => 'en', 'name' => 'English']);
        $tag = Tag::create(['name' => 'web']);
        $translation = Translation::create(['key' => 'home.title', 'description' => '']);
        TranslationEntry::create([
            'translation_id' => $translation->id,
            'language_id' => $language->id,
            'tag_id' => $tag->id,
            'locale' => 'en',
            'content' => 'Welcome'
        ]);

        $response = $this->getJson('/api/translations?key=home');

        $response->assertOk()
                 ->assertJsonFragment(['key' => 'home.title']);
    }

    public function test_can_export_json_translations()
    {
        $language = Language::create(['locale' => 'en', 'name' => 'English']);
        $tag = Tag::create(['name' => 'web']);
        $translation = Translation::create(['key' => 'home.title', 'description' => '']);
        TranslationEntry::create([
            'translation_id' => $translation->id,
            'language_id' => $language->id,
            'tag_id' => $tag->id,
            'locale' => 'en',
            'content' => 'Welcome'
        ]);

        $response = $this->getJson('/api/translations/export-json?locale=en');

        $response->assertOk()
                 ->assertSee('home.title')
                 ->assertSee('Welcome');
    }
}
