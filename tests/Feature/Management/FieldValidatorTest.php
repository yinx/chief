<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModel;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelTranslation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerWithValidationFake;
use Thinktomorrow\Chief\Tests\TestCase;

class FieldValidatorTest extends TestCase
{
    private $fake;
    private $model;

    protected function setUp()
    {
        parent::setUp();

        ManagedModel::migrateUp();
        ManagedModelTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register('fakes', ManagerWithValidationFake::class);

        $this->model = ManagedModel::create(['title' => 'Foobar', 'custom_column' => 'custom']);
        $this->fake = app(ManagerWithValidationFake::class)->manage($this->model);
    }

    /** @test */
    public function a_required_field_can_be_validated()
    {
        $this->assertValidation(new ManagedModel(), 'title', $this->payload(['title' => '']),
            $this->fake->route('edit'),
            $this->fake->route('update'),
            1, 'put'
        );
    }

    /** @test */
    public function a_required_translatable_field_can_be_validated()
    {
        $this->assertValidation(new ManagedModel(), 'trans.nl.title_trans', $this->payload(['trans.nl.title_trans' => '']),
            $this->fake->route('edit'),
            $this->fake->route('update'),
            1, 'put'
        );
    }

    protected function payload($overrides = [])
    {
        $params = [
            'title' => 'title updated',
            'custom' => 'custom updated',
            'trans' => [
                'nl' => [
                    'title_trans' => 'title_trans nl updated',
                ],
                'en' => [
                    'title_trans' => 'title_trans en updated',
                ],
            ],
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }
}
