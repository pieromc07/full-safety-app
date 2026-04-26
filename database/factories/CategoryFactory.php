<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
  protected $model = \App\Models\Category::class;

  public function definition(): array
  {
    return [
      'name' => fake()->unique()->words(2, true),
      'parent_id' => null,
      'id_targeted_rels_inspections' => null,
      'cuid_inserted' => DB::raw('CUID_19D_B10()'),
    ];
  }

  public function childOf(\App\Models\Category $parent): static
  {
    return $this->state(fn () => ['parent_id' => $parent->id_categories]);
  }
}
