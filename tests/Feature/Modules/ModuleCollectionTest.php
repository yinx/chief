<?php

namespace Thinktomorrow\Chief\Tests\Feature\Modules;

use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Tests\Fakes\OtherModuleFake;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;

class ModuleCollectionTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief.collections.modules', [
            'newsletter' => NewsletterModuleFake::class,
            'others' => OtherModuleFake::class,
        ]);
    }

    /** @test */
    public function a_module_requires_a_collection()
    {
        $this->expectException(\PDOException::class);

        Module::create(['collection' => null, 'slug' => 'foobar']);
    }

    /** @test */
    public function a_module_can_be_divided_by_collection()
    {
        Module::create(['collection' => 'newsletter', 'slug' => 'foobar']);

        $this->assertCount(1, NewsletterModuleFake::all());
        $this->assertCount(0, OtherModuleFake::all());
        $this->assertCount(0, Module::all());
        $this->assertCount(1, Module::ignoreCollection()->get());
    }

    /** @test */
    public function a_module_can_be_retrieved_by_collection()
    {
        Module::create(['collection' => 'newsletter', 'slug' => 'foobar']);

        $this->assertNotNull(NewsletterModuleFake::first());
        $this->assertNull(OtherModuleFake::first());
        $this->assertNull(Module::first());
    }

    /** @test */
    public function collection_scope_can_be_ignored()
    {
        Module::create(['collection' => 'newsletter', 'slug' => 'foobar']);

        $this->assertNotNull(Module::ignoreCollection()->first());
    }

    /** @test */
    public function collection_scope_can_be_set_on_runtime()
    {
        Module::create(['collection' => 'newsletter', 'slug' => 'foobar']);

        $this->assertNotNull(Module::collection('newsletter')->first());
        $this->assertNull(Module::collection('others')->first());
        $this->assertNull(Module::collection('hero')->first());
        $this->assertNull(Module::collection(null)->first());
    }

    /** @test */
    public function it_can_retrieve_all_available_collections()
    {
        Module::create(['collection' => 'newsletter', 'slug' => 'foobar']);
        Module::create(['collection' => 'others', 'slug' => 'foobar-2']);
        Module::create(['collection' => 'newsletter', 'slug' => 'foobar-3']);

        $this->assertEquals(['newsletter', 'others'], Module::freshAvailableCollections()->keys()->toArray());
    }

    /** @test */
    public function it_returns_the_right_collection_with_the_eloquent_find_methods()
    {
        $module = NewsletterModuleFake::create([
            'collection' => 'newsletter',
            'slug' => 'foobar',
        ]);

        $this->assertInstanceOf(NewsletterModuleFake::class, Module::ignoreCollection()->find($module->id));
        $this->assertInstanceOf(NewsletterModuleFake::class, Module::ignoreCollection()->findOrFail($module->id));
    }

    /** @test */
    public function it_returns_the_right_collection_model_by_slug()
    {
        NewsletterModuleFake::create([
            'collection' => 'newsletter',
            'slug' => 'foobar',
        ]);

        $this->assertInstanceOf(NewsletterModuleFake::class, Module::findBySlug('foobar'));
    }
}