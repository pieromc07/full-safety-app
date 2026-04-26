<?php

namespace Tests\Feature\Api;

use App\Models\CheckPoint;
use App\Models\Enterprise;
use App\Models\InspectionType;
use App\Models\Targeted;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Tests\Traits\AuthenticatesApiUser;

class SyncTest extends TestCase
{
  use DatabaseTransactions, AuthenticatesApiUser;

  protected function setUp(): void
  {
    parent::setUp();
    Mail::fake();
  }

  public function test_get_sync_returns_full_catalog_shape(): void
  {
    $resp = $this->getJson('/api/sync', $this->bearer($this->masterUser()));

    $resp->assertOk()
      ->assertJsonStructure([
        'inspectionsType',
        'enterprisesType',
        'enterprises',
        'enterprisesRelsEnterprise',
        'categories',
        'subcategories',
        'evidences',
        'targeteds',
        'targetedRelsInspections',
        'targetedRelsLoadTypes',
        'checkPoints',
        'employees',
        'unitsType',
        'productsType',
        'products',
        'productsRelsEnterprise',
      ]);
  }

  public function test_daily_dialog_creates_record(): void
  {
    $user = $this->masterUser();
    $checkpoint = CheckPoint::factory()->create();
    $supplier = Enterprise::factory()->create();
    $transport = Enterprise::factory()->create();

    $payload = [
      'dialogue' => json_encode([
        'date' => '15/05/2026',
        'hour' => '08:30',
        'checkpoint_id' => $checkpoint->id_checkpoints,
        'supplier_enterprise_id' => $supplier->id_enterprises,
        'transport_enterprise_id' => $transport->id_enterprises,
        'topic' => 'Manejo defensivo',
        'participants' => 5,
      ]),
      'user' => json_encode(['id' => $user->id_users]),
    ];

    $resp = $this->postJson('/api/dialogue', $payload, $this->bearer($user));

    $resp->assertCreated()
      ->assertJson(['status' => true])
      ->assertJsonStructure(['data' => ['id']]);

    $this->assertDatabaseHas('daily_dialogs', [
      'topic' => 'Manejo defensivo',
      'id_users' => $user->id_users,
    ]);
  }

  public function test_daily_dialog_rejects_missing_dialogue_with_422(): void
  {
    $resp = $this->postJson('/api/dialogue', [
      'user' => json_encode(['id' => 1]),
    ], $this->bearer($this->masterUser()));

    $resp->assertStatus(422)->assertJsonValidationErrors('dialogue');
  }

  public function test_active_pause_rejects_missing_pauseactive_with_422(): void
  {
    $resp = $this->postJson('/api/pauseactive', [
      'user' => json_encode(['id' => 1]),
    ], $this->bearer($this->masterUser()));

    $resp->assertStatus(422)->assertJsonValidationErrors('pauseactive');
  }

  public function test_alcohol_test_rejects_missing_alcoholtest_with_422(): void
  {
    $resp = $this->postJson('/api/alcoholtest', [
      'user' => json_encode(['id' => 1]),
    ], $this->bearer($this->masterUser()));

    $resp->assertStatus(422)->assertJsonValidationErrors('alcoholtest');
  }

  public function test_control_gps_rejects_missing_controlgps_with_422(): void
  {
    $resp = $this->postJson('/api/controlgps', [
      'user' => json_encode(['id' => 1]),
    ], $this->bearer($this->masterUser()));

    $resp->assertStatus(422)->assertJsonValidationErrors('controlgps');
  }

  public function test_active_pause_creates_record(): void
  {
    $user = $this->masterUser();
    $checkpoint = CheckPoint::factory()->create();
    $supplier = Enterprise::factory()->create();
    $transport = Enterprise::factory()->create();

    $payload = [
      'pauseactive' => json_encode([
        'date' => '15/05/2026',
        'hour' => '10:00',
        'checkpoint_id' => $checkpoint->id_checkpoints,
        'supplier_enterprise_id' => $supplier->id_enterprises,
        'transport_enterprise_id' => $transport->id_enterprises,
        'participants' => 4,
      ]),
      'user' => json_encode(['id' => $user->id_users]),
    ];

    $resp = $this->postJson('/api/pauseactive', $payload, $this->bearer($user));

    $resp->assertCreated()->assertJson(['status' => true]);
    $this->assertDatabaseHas('active_pauses', ['participants' => 4]);
  }

  public function test_alcohol_test_creates_record_without_details(): void
  {
    $user = $this->masterUser();
    $checkpoint = CheckPoint::factory()->create();
    $supplier = Enterprise::factory()->create();
    $transport = Enterprise::factory()->create();

    $payload = [
      'alcoholtest' => json_encode([
        'date' => '15/05/2026',
        'hour' => '11:30',
        'checkpoint_id' => $checkpoint->id_checkpoints,
        'supplier_enterprise_id' => $supplier->id_enterprises,
        'transport_enterprise_id' => $transport->id_enterprises,
        'details' => [],
      ]),
      'user' => json_encode(['id' => $user->id_users]),
    ];

    $resp = $this->postJson('/api/alcoholtest', $payload, $this->bearer($user));

    $resp->assertCreated()
      ->assertJson(['status' => true, 'data' => ['saved_details' => 0]]);
  }

  public function test_control_gps_creates_record(): void
  {
    $user = $this->masterUser();
    $checkpoint = CheckPoint::factory()->create();
    $supplier = Enterprise::factory()->create();
    $transport = Enterprise::factory()->create();

    $payload = [
      'controlgps' => json_encode([
        'date' => '15/05/2026',
        'hour' => '13:00',
        'checkpoint_id' => $checkpoint->id_checkpoints,
        'supplier_enterprise_id' => $supplier->id_enterprises,
        'transport_enterprise_id' => $transport->id_enterprises,
        'option' => 1,
        'state' => 1,
        'observation' => 'Control rutinario',
      ]),
      'user' => json_encode(['id' => $user->id_users]),
    ];

    $resp = $this->postJson('/api/controlgps', $payload, $this->bearer($user));

    $resp->assertCreated()->assertJson(['status' => true]);
    $this->assertDatabaseHas('gps_controls', ['observation' => 'Control rutinario']);
  }

  public function test_inspection_massive_rejects_empty_list(): void
  {
    $resp = $this->postJson('/api/inspections/massive', [
      'inspections' => json_encode([]),
    ], $this->bearer($this->masterUser()));

    $resp->assertStatus(400)->assertJson(['status' => false]);
  }

  public function test_inspection_massive_rejects_missing_inspections_key(): void
  {
    $resp = $this->postJson('/api/inspections/massive', [
    ], $this->bearer($this->masterUser()));

    $resp->assertStatus(400)->assertJson(['status' => false]);
  }

  public function test_inspection_massive_creates_inspection(): void
  {
    $user = $this->masterUser();
    $type = InspectionType::factory()->create();
    $checkpoint = CheckPoint::factory()->create();
    $supplier = Enterprise::factory()->create();
    $transport = Enterprise::factory()->create();
    $targeted = Targeted::factory()->create();

    $payload = [
      'inspections' => json_encode([
        [
          'inspection' => [
            'date' => '15/05/2026',
            'hour' => '14:00',
            'inspection_type_id' => $type->id_inspection_types,
            'supplier_enterprise_id' => $supplier->id_enterprises,
            'transport_enterprise_id' => $transport->id_enterprises,
            'checkpoint_id' => $checkpoint->id_checkpoints,
            'targeted_id' => $targeted->id_targeteds,
            'user_id' => $user->id_users,
          ],
          'evidences' => [],
        ],
      ]),
    ];

    $resp = $this->postJson('/api/inspections/massive', $payload, $this->bearer($user));

    $resp->assertCreated()
      ->assertJson(['status' => true])
      ->assertJsonStructure(['data' => ['created_ids', 'created_count']]);

    $this->assertSame(1, $resp->json('data.created_count'));
  }
}
