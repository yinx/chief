<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages\Media;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Models\Asset;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;

class UploadMediaTest extends TestCase
{
    use PageFormParams;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();
    }

    /** @test */
    public function a_new_asset_can_be_uploaded()
    {
        $page = Page::create(['collection' => 'singles']);

        config()->set(['app.fallback_locale' => 'nl']);

        // Upload asset
        $this->asAdmin()
            ->put(route('chief.back.pages.update', $page->id), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'new' => [
                            $this->dummySlimImagePayload(),
                        ]
                    ]
                ]
            ]));


        $this->assertTrue($page->hasFile(MediaType::HERO));
        $this->assertCount(1, $page->getAllFiles(MediaType::HERO));
    }

    /** @test */
    public function a_new_asset_can_be_uploaded_as_regular_file()
    {
        $this->disableExceptionHandling();
        $page = Page::create(['collection' => 'singles']);

        config()->set(['app.fallback_locale' => 'nl']);

        $this->asAdmin()
            ->put(route('chief.back.pages.update', $page->id), $this->validUpdatePageParams([
                'files' => [
                    MediaType::DOCUMENT => [
                        'new' => [
                            UploadedFile::fake()->create('fake.pdf')
                        ]
                    ]
                ]
            ]));

        $this->assertTrue($page->hasFile(MediaType::DOCUMENT));
        $this->assertCount(1, $page->getAllFiles(MediaType::DOCUMENT));
    }

    /** @test */
    public function an_asset_can_be_replaced()
    {
        $page = Page::create(['collection' => 'singles']);
        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);

        $existing_asset = $page->getAllFiles(MediaType::HERO)->first();

        // Replace asset
        $this->asAdmin()
            ->put(route('chief.back.pages.update', $page->id), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'replace' => [
                            $existing_asset->id => $this->dummySlimImagePayload(),
                        ]
                    ]
                ]
            ]));

        // Assert replacement took place
        $this->assertCount(1, $page->fresh()->getAllFiles(MediaType::HERO));
        $this->assertContains('tt-favicon.png', $page->fresh()->getFileUrl(MediaType::HERO));
    }

    /** @test */
    public function an_asset_can_be_removed()
    {
        $page = Page::create(['collection' => 'singles']);
        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);

        // Assert Image is there
        $this->assertTrue($page->hasFile(MediaType::HERO));
        $this->assertCount(1, $page->getAllFiles(MediaType::HERO));

        // Remove asset
        $response = $this->asAdmin()
            ->put(route('chief.back.pages.update', $page->id), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'delete' => [
                            $page->getAllFiles(MediaType::HERO)->first()->id,
                        ]
                    ]
                ]
            ]));

        // Assert Image is no longer there
        $this->assertFalse($page->fresh()->hasFile(MediaType::HERO));
        $this->assertCount(0, $page->fresh()->getAllFiles());
    }

    /** @test */
    public function an_asset_can_be_sorted()
    {
        $page = Page::create(['collection' => 'singles']);
        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);
        $page->addFile(UploadedFile::fake()->image('image2.png'), MediaType::HERO);

        $images = $page->fresh()->getAllFiles(MediaType::HERO);

        $this->asAdmin()
            ->put(route('chief.back.pages.update', $page->id), $this->validUpdatePageParams([
                'filesOrder' => [
                    MediaType::HERO => $images->last()->id . ',' . $images->first()->id,
                ]
            ]));

        $this->assertEquals([$images->last()->id, $images->first()->id], $page->fresh()->getAllFiles(MediaType::HERO)->pluck('id')->toArray());
    }

    /** @test */
    public function it_can_upload_image_with_uppercased_extension()
    {
        // Currently uploaded a xxx.JPEG fails retrieval as the source by Slim
        // TODO: this is something that should be provided by Assetlibrary
        $this->markTestIncomplete();

        $page = Page::create(['collection' => 'singles']);

        $this->asAdmin()
            ->put(route('chief.back.pages.update', $page->id), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'new' => [
                            $this->dummySlimImagePayload('tt-favicon.PNG'),
                        ]
                    ]
                ]
            ]));

        $this->assertEquals('tt-favicon.png', $page->getFilename(MediaType::HERO));
    }
}
