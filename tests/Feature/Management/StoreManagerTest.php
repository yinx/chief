<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModel;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelTranslation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\TestCase;

class StoreManagerTest extends TestCase
{
    private $fake;

    protected function setUp()
    {
        parent::setUp();

        ManagedModel::migrateUp();
        ManagedModelTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register('fakes', ManagerFake::class);

//        $this->model = ManagedModel::create(['title' => 'Foobar', 'custom_column' => 'custom']);
        $this->fake = app(ManagerFake::class);
    }

    /** @test */
    public function it_can_create_a_field()
    {
        $this->disableExceptionHandling();
        $this->asAdmin()
            ->post($this->fake->route('store'), [
                'title' => 'foobar-created',
            ]);

        $this->assertEquals('foobar-created', ManagedModel::first()->title);
    }

    /** @test */
    public function it_can_create_a_field_with_custom_column_name()
    {
        $this->asAdmin()
            ->post($this->fake->route('store'), [
                'custom' => 'custom-created',
            ]);

        $this->assertEquals('custom-created', ManagedModel::first()->custom_column);
    }

    /** @test */
    public function it_can_create_a_translatable_field()
    {
        $this->asAdmin()
            ->post($this->fake->route('store'), [
                'trans' => [
                    'nl' => [
                        'title_trans' => 'title-nl-created',
                        'content_trans' => 'content-nl-created',
                    ],
                    'en' => [
                        'title_trans' => 'title-en-created',
                    ]
                ],
            ]);

        $first = ManagedModel::first();

        $this->assertEquals('title-nl-created', $first->title_trans);
        $this->assertEquals('content-nl-created', $first->content_trans);

        $this->assertEquals('title-en-created', $first->getTranslationFor('title_trans', 'en'));
        $this->assertNull($first->getTranslationFor('content_trans', 'en'));
    }

    /** @test */
    public function it_can_create_a_media_field()
    {
        $this->asAdmin()
            ->post($this->fake->route('store'), [
                'files' => [
                    'hero' => [
                        'new' => [
                            $this->dummySlimImagePayload('tt-favicon.png'),
                        ]
                    ]
                ],
            ]);

        $this->assertEquals('tt-favicon.png', ManagedModel::first()->getFilename('hero'));
    }
}
