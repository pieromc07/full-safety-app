<?php

namespace Tests\Feature\Web;

use App\Models\Employee;
use App\Models\Enterprise;
use App\Models\EnterpriseType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\AuthenticatesWebUser;

class EmployeeCrudTest extends TestCase
{
  use DatabaseTransactions, AuthenticatesWebUser;

  protected function setUp(): void
  {
    parent::setUp();
    $this->loginAsMaster();
  }

  public function test_index_renders(): void
  {
    $this->get(route('employee'))->assertOk();
  }

  public function test_can_store_employee(): void
  {
    $transportType = EnterpriseType::firstWhere('id_enterprise_types', 2)
      ?? EnterpriseType::factory()->create(['id_enterprise_types' => 2]);
    $transport = Enterprise::factory()->create(['id_enterprise_types' => $transportType->id_enterprise_types]);

    $this->post(route('employee.store'), [
      'document' => '12345678',
      'name' => 'Juan',
      'lastname' => 'Pérez',
      'id_transport_enterprises' => $transport->id_enterprises,
    ])
      ->assertRedirect(route('employee'))
      ->assertSessionHas('success');

    $this->assertDatabaseHas('employees', [
      'document' => '12345678',
      'fullname' => 'Juan Pérez',
    ]);
  }

  public function test_store_validates_required_document(): void
  {
    $transport = Enterprise::factory()->create();

    $this->post(route('employee.store'), [
      'name' => 'Sin',
      'lastname' => 'Documento',
      'id_transport_enterprises' => $transport->id_enterprises,
    ])->assertSessionHasErrors('document');
  }

  public function test_destroy_blocks_when_employee_has_user(): void
  {
    $employee = Employee::factory()->create([
      'id_users' => \App\Models\User::factory()->create()->id_users,
    ]);

    $this->delete(route('employee.destroy', $employee))
      ->assertSessionHas('error');

    $this->assertNull($employee->fresh()->cuid_deleted);
  }

  public function test_destroy_soft_deletes_employee_without_user(): void
  {
    $employee = Employee::factory()->create();

    $this->delete(route('employee.destroy', $employee))
      ->assertSessionHas('success');

    $this->assertNotNull(
      \DB::table('employees')->where('id_employees', $employee->id_employees)->value('cuid_deleted')
    );
  }
}
