<?php

namespace Chief\Tests\Feature\Authorization;

use Chief\Authorization\Role;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;
use Chief\Users\User;

class UpdateRoleTest extends TestCase
{
    use ChiefDatabaseTransactions;

    private $newRole;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->setUpDefaultAuthorization();

        // Create a new role first
        $role = Role::create(['name' => array_get($this->validParams(),'name')]);
        $role->givePermissionTo(array_get($this->validParams(),'permission_names'));
        $this->newRole = $role;
    }

    /** @test */
    function only_developer_can_view_the_update_form()
    {
        $response = $this->actingAs($this->developer(), 'admin')->get(route('back.roles.edit', Role::first()->id));
        $response->assertStatus(200);
    }

    /** @test */
    function regular_admin_cannot_view_the_update_form()
    {
        $response = $this->actingAs(factory(User::class)->create(), 'admin')->get(route('back.roles.edit', Role::first()->id));

        $response->assertStatus(302)
            ->assertRedirect(route('back.dashboard'))
            ->assertSessionHas('messages.error');
    }

    /** @test */
    function updating_a_role()
    {
        // Now update it
        $response = $this->actingAs($this->developer(), 'admin')
            ->put(route('back.roles.update', $this->newRole->id), $this->validUpdateParams());

        $response->assertStatus(302)
            ->assertRedirect(route('back.roles.index'))
            ->assertSessionHas('messages.success');

        $this->assertUpdatedValues($this->newRole->fresh());
    }

    /** @test */
    function only_authenticated_developer_can_update_a_role()
    {
        $response = $this->put(route('back.roles.update', $this->newRole->id), $this->validUpdateParams());

        $response->assertRedirect(route('back.login'));

        // Assert nothing was updated
        $this->assertNewValues($this->newRole->fresh());
    }

    /** @test */
    function when_updating_role_name_is_required()
    {
        //$this->disableExceptionHandling();

        $this->assertValidation(new Role(), 'name', $this->validUpdateParams(['name' => '']),
            route('back.roles.index'),
            route('back.roles.update', $this->newRole->id),
            Role::count(),
            'put'
        );
    }

    /** @test */
    function when_updating_role_permissions_are_required()
    {
        $this->assertValidation(new Role(), 'permission_names', $this->validParams(['permission_names' => '']),
            route('back.roles.index'),
            route('back.roles.update', $this->newRole->id),
            Role::count(),
            'put'
        );
    }

    /** @test */
    function when_updating_role_name_must_be_unique()
    {
        $this->assertValidation(new Role(), 'name', $this->validParams(['name' => 'developer']),
            route('back.roles.index'),
            route('back.roles.update', $this->newRole->id),
            Role::count(),
            'put'
        );
    }

    /** @test */
    function when_updating_role_permissions_must_be_passed_as_array()
    {
        $this->assertValidation(new Role(), 'permission_names', $this->validParams(['permission_names' => 'view-role']),
            route('back.roles.index'),
            route('back.roles.update', $this->newRole->id),
            Role::count(),
            'put'
        );
    }

    private function validParams($overrides = [])
    {
        $params = [
            'name' => 'new name',
            'permission_names' => ['create-role', 'update-role'],
        ];

        foreach ($overrides as $key => $value){
            array_set($params,  $key, $value);
        }

        return $params;
    }

    private function validUpdateParams($overrides = [])
    {
        return $this->validParams(array_merge([
            'name' => 'updated name',
            'permission_names' => ['view-role']
        ],$overrides));
    }

    private function assertNewValues(Role $role)
    {
        $this->assertEquals('new name', $role->name);
        $this->assertEquals(['create-role', 'update-role'], $role->permissions->pluck('name')->toArray());
    }

    private function assertUpdatedValues(Role $role)
    {
        $this->assertEquals('updated name', $role->name);
        $this->assertEquals(['view-role'], $role->permissions->pluck('name')->toArray());
    }
}