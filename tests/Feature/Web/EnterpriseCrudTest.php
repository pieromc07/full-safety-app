<?php

namespace Tests\Feature\Web;

use App\Models\Enterprise;
use App\Models\EnterpriseType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\AuthenticatesWebUser;

class EnterpriseCrudTest extends TestCase
{
  use DatabaseTransactions, AuthenticatesWebUser;

  protected function setUp(): void
  {
    parent::setUp();
    $this->loginAsMaster();
  }

  public function test_index_renders(): void
  {
    $this->get(route('enterprise'))->assertOk();
  }

  public function test_index_filtered_by_type_renders(): void
  {
    $type = EnterpriseType::first();
    $this->get(route('enterprise', ['id_enterprise_types' => $type->id_enterprise_types]))
      ->assertOk();
  }

  public function test_can_store_enterprise(): void
  {
    $type = EnterpriseType::first();

    $this->post(route('enterprise.store'), [
      'name' => 'ACME SAC',
      'ruc' => '20123456789',
      'id_enterprise_types' => $type->id_enterprise_types,
    ])
      ->assertRedirect(route('enterprise'))
      ->assertSessionHas('success');

    $this->assertDatabaseHas('enterprises', ['ruc' => '20123456789']);
  }

  public function test_store_validates_ruc_size(): void
  {
    $type = EnterpriseType::first();

    $this->post(route('enterprise.store'), [
      'name' => 'BadRuc',
      'ruc' => '123',
      'id_enterprise_types' => $type->id_enterprise_types,
    ])->assertSessionHasErrors('ruc');
  }

  public function test_assign_creates_supplier_transport_relation(): void
  {
    $supplier = Enterprise::factory()->create();
    $transport = Enterprise::factory()->create();

    $this->post(route('enterprise.assign'), [
      'id_supplier_enterprises' => $supplier->id_enterprises,
      'id_transport_enterprises' => $transport->id_enterprises,
    ])
      ->assertRedirect(route('enterprise'))
      ->assertSessionHas('success');

    $this->assertDatabaseHas('enterprise_rels_enterprises', [
      'id_supplier_enterprises' => $supplier->id_enterprises,
      'id_transport_enterprises' => $transport->id_enterprises,
    ]);
  }

  public function test_destroy_soft_deletes(): void
  {
    $enterprise = Enterprise::factory()->create();

    $this->delete(route('enterprise.destroy', $enterprise))
      ->assertRedirect(route('enterprise'));

    $this->assertNotNull(
      \DB::table('enterprises')->where('id_enterprises', $enterprise->id_enterprises)->value('cuid_deleted')
    );
  }
}
