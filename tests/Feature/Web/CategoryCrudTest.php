<?php

namespace Tests\Feature\Web;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\AuthenticatesWebUser;

class CategoryCrudTest extends TestCase
{
  use DatabaseTransactions, AuthenticatesWebUser;

  protected function setUp(): void
  {
    parent::setUp();
    $this->loginAsMaster();
  }

  public function test_categories_index_renders(): void
  {
    $this->get(route('category'))->assertOk();
  }

  public function test_subcategories_index_renders(): void
  {
    $this->get(route('category1'))->assertOk();
  }

  public function test_can_store_root_category(): void
  {
    $this->post(route('category.store'), [
      'name' => 'Categoría raíz',
    ])
      ->assertRedirect(route('category'))
      ->assertSessionHas('success');

    $this->assertDatabaseHas('categories', [
      'name' => 'Categoría raíz',
      'parent_id' => null,
    ]);
  }

  public function test_can_store_subcategory_redirects_to_subcategories(): void
  {
    $parent = Category::factory()->create();

    $this->post(route('category.store'), [
      'name' => 'Subcategoría',
      'parent_id' => $parent->id_categories,
    ])
      ->assertRedirect(route('category1'))
      ->assertSessionHas('success');

    $this->assertDatabaseHas('categories', [
      'name' => 'Subcategoría',
      'parent_id' => $parent->id_categories,
    ]);
  }

  public function test_destroy_blocks_when_category_has_subcategories(): void
  {
    $parent = Category::factory()->create();
    Category::factory()->childOf($parent)->create();

    $this->delete(route('category.destroy', $parent))
      ->assertRedirect(route('category'))
      ->assertSessionHas('error');

    $this->assertNull($parent->fresh()->cuid_deleted);
  }

  public function test_destroy_soft_deletes_leaf(): void
  {
    $cat = Category::factory()->create();

    $this->delete(route('category.destroy', $cat))
      ->assertSessionHas('success');

    $this->assertNotNull(
      \DB::table('categories')->where('id_categories', $cat->id_categories)->value('cuid_deleted')
    );
  }
}
