<?php

namespace Tests\Feature\Web;

use App\Models\Category;
use App\Models\Evidence;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\AuthenticatesWebUser;

class EvidenceCrudTest extends TestCase
{
  use DatabaseTransactions, AuthenticatesWebUser;

  protected function setUp(): void
  {
    parent::setUp();
    $this->loginAsMaster();
  }

  public function test_index_renders(): void
  {
    $this->get(route('evidences'))->assertOk();
  }

  public function test_store_creates_evidence(): void
  {
    $parent = Category::factory()->create();
    $sub = Category::factory()->childOf($parent)->create();

    $resp = $this->post(route('evidences.store'), [
      'name' => 'Cinturón de seguridad',
      'description' => 'Verificar uso correcto',
      'id_subcategories' => $sub->id_categories,
    ]);

    $resp->assertRedirect(route('evidences'))
      ->assertSessionHas('success');

    $this->assertDatabaseHas('evidences', [
      'name' => 'Cinturón de seguridad',
      'id_subcategories' => $sub->id_categories,
    ]);
  }

  public function test_store_validates_required_name(): void
  {
    $sub = Category::factory()->childOf(Category::factory()->create())->create();

    $this->post(route('evidences.store'), [
      'description' => 'sin nombre',
      'id_subcategories' => $sub->id_categories,
    ])->assertSessionHasErrors('name');
  }

  public function test_update_changes_name(): void
  {
    $evidence = Evidence::factory()->create();

    $this->put(route('evidences.update', $evidence), [
      'name' => 'Nombre actualizado',
      'id_subcategories' => $evidence->id_subcategories,
    ])
      ->assertRedirect(route('evidences'))
      ->assertSessionHas('success');

    $this->assertSame('Nombre actualizado', $evidence->fresh()->name);
  }

  public function test_destroy_soft_deletes_via_cuid(): void
  {
    $evidence = Evidence::factory()->create();

    $this->delete(route('evidences.destroy', $evidence))
      ->assertRedirect(route('evidences'))
      ->assertSessionHas('success');

    $this->assertNotNull(
      \DB::table('evidences')->where('id_evidences', $evidence->id_evidences)->value('cuid_deleted')
    );
  }

  public function test_category_accessor_resolves_via_subcategory(): void
  {
    $parent = Category::factory()->create(['name' => 'Vehículo']);
    $sub = Category::factory()->childOf($parent)->create(['name' => 'Frenos']);
    $evidence = Evidence::factory()->create(['id_subcategories' => $sub->id_categories]);

    $this->assertSame('Vehículo', $evidence->category->name);
    $this->assertSame('Frenos', $evidence->subcategory->name);
  }
}
