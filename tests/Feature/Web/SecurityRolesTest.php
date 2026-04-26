<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tests\Traits\AuthenticatesWebUser;

class SecurityRolesTest extends TestCase
{
  use DatabaseTransactions, AuthenticatesWebUser;

  protected function setUp(): void
  {
    parent::setUp();
    $this->loginAsMaster();
  }

  public function test_roles_index_renders(): void
  {
    $this->get(route('roles'))->assertOk();
  }

  public function test_permissions_index_renders(): void
  {
    $this->get(route('permissions'))->assertOk();
  }

  public function test_users_index_renders(): void
  {
    $this->get(route('users'))->assertOk();
  }

  public function test_can_create_role(): void
  {
    $this->post(route('roles.store'), [
      'name' => 'analyst',
      'description' => 'Analista',
    ])
      ->assertRedirect(route('roles'))
      ->assertSessionHas('success');

    $this->assertDatabaseHas('roles', ['name' => 'analyst']);
  }

  public function test_role_create_requires_name(): void
  {
    $this->post(route('roles.store'), [
      'description' => 'Sin nombre',
    ])->assertSessionHasErrors('name');
  }

  public function test_master_role_cannot_be_updated(): void
  {
    $master = Role::where('name', 'master')->firstOrFail();

    $this->put(route('roles.update', $master), [
      'name' => 'master-renamed',
      'description' => 'Intento de cambio',
    ])
      ->assertRedirect(route('roles'))
      ->assertSessionHas('error');

    $this->assertSame('master', $master->fresh()->name);
  }

  public function test_can_create_permission(): void
  {
    $this->post(route('permissions.store'), [
      'name' => 'reports.export',
      'description' => 'Exportar reportes',
      'group' => 'reports',
    ])
      ->assertRedirect()
      ->assertSessionHas('success');

    $this->assertDatabaseHas('permissions', ['name' => 'reports.export']);
  }

  public function test_assigning_permission_to_role(): void
  {
    $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);
    $perm = Permission::create(['name' => 'tmp.action', 'guard_name' => 'web']);

    $role->givePermissionTo($perm);

    $this->assertTrue($role->fresh()->hasPermissionTo('tmp.action'));
  }
}
