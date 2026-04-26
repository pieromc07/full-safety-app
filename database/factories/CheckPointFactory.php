<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CheckPoint>
 */
class CheckPointFactory extends Factory
{
  protected $model = \App\Models\CheckPoint::class;

  public function definition(): array
  {
    return [
      'name' => fake()->unique()->words(2, true),
      'description' => fake()->sentence(),
      'cuid_inserted' => DB::raw('CUID_19D_B10()'),
    ];
  }
}
