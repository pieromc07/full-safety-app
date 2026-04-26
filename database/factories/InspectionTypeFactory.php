<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InspectionType>
 */
class InspectionTypeFactory extends Factory
{
  protected $model = \App\Models\InspectionType::class;

  public function definition(): array
  {
    return [
      'name' => substr(fake()->unique()->word(), 0, 32),
      'description' => fake()->sentence(),
      'cuid_inserted' => DB::raw('CUID_19D_B10()'),
    ];
  }
}
