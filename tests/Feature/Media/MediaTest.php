<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages\Media;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\TestCase;

class MediaTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'singles' => Single::class,
            'articles' => ArticlePageFake::class,
        ]);
    }
    
    /** @test */
    public function it_can_have_an_image()
    {
        $fake = ArticlePageFake::create([]);

        $fake->addFile(UploadedFile::fake()->image('image.png'), 'images');

        $this->assertCount(1, $fake->assets);
    }

    /** @test */
    public function a_page_can_have_an_image_for_hero()
    {
        $page = Page::create(['collection' => 'singles']);

        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);

        $this->assertTrue($page->hasFile(MediaType::HERO));
        $this->assertCount(1, $page->getAllFiles(MediaType::HERO));
    }

    /** @test */
    public function it_can_add_image_via_wysiwyg_editor()
    {
        $this->setUpDefaultAuthorization();

        config()->set(['app.fallback_locale' => 'nl']);

        $article = ArticlePageFake::create(['collection' => 'articles']);

        $response = $this->asAdmin()->post(route('pages.media.upload', $article->id), [
            'file' => [
                UploadedFile::fake()->image('image.png')
            ],
        ]);


        $this->assertTrue($article->hasFile(MediaType::CONTENT));

        $assets = $article->getAllFiles(MediaType::CONTENT);

        $this->assertCount(1, $assets);

        $response->assertStatus(201)
                 ->assertJson([
                        "file-".$assets->first()->id => [
                            "url" => $article->getFileUrl(MediaType::CONTENT),
                            "id" => $assets->first()->id,
                        ]
                 ]);
    }
}
