<?php

namespace Tests\Feature\Web;

use App\Models\Targeted;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\AuthenticatesWebUser;

class TargetedCrudTest extends TestCase
{
  use DatabaseTransactions, AuthenticatesWebUser;

  protected function setUp(): void
  {
    parent::setUp();
    $this->loginAsMaster();
  }

  public function test_targeted_index_renders(): void
  {
    $this->get(route('targeted'))->assertOk();
  }

  public function test_target_index_renders(): void
  {
    $this->get(route('target'))->assertOk();
  }

  public function test_can_store_root_targeted(): void
  {
    $this->post(route('targeted.store'), [
      'name' => 'Persona',
    ])
      ->assertRedirect(route('targeted'))
      ->assertSessionHas('success');

    $this->assertDatabaseHas('targeteds', [
      'name' => 'Persona',
      'targeted_id' => null,
    ]);
  }

  public function test_can_store_child_redirects_to_target(): void
  {
    $parent = Targeted::factory()->create();

    $this->post(route('targeted.store'), [
      'name' => 'Conductor',
      'targeted_id' => $parent->id_targeteds,
    ])
      ->assertRedirect(route('target'))
      ->assertSessionHas('success');
  }

  public function test_destroy_blocks_when_targeted_has_children(): void
  {
    $parent = Targeted::factory()->create();
    Targeted::factory()->childOf($parent)->create();

    $this->delete(route('targeted.destroy', $parent))
      ->assertSessionHas('error');

    $this->assertNull($parent->fresh()->cuid_deleted);
  }
}
