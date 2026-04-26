<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EnterpriseType>
 */
class EnterpriseTypeFactory extends Factory
{
  protected $model = \App\Models\EnterpriseType::class;

  public function definition(): array
  {
    return [
      'name' => fake()->unique()->word(),
      'description' => fake()->sentence(),
      'cuid_inserted' => DB::raw('CUID_19D_B10()'),
    ];
  }
}
