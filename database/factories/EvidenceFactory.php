<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evidence>
 */
class EvidenceFactory extends Factory
{
  protected $model = \App\Models\Evidence::class;

  public function definition(): array
  {
    return [
      'name' => fake()->unique()->words(3, true),
      'description' => fake()->sentence(),
      'id_subcategories' => Category::factory()
        ->childOf(Category::factory()->create()),
      'cuid_inserted' => DB::raw('CUID_19D_B10()'),
    ];
  }
}
