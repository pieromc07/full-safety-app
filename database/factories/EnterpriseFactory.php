<?php

namespace Database\Factories;

use App\Models\EnterpriseType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enterprise>
 */
class EnterpriseFactory extends Factory
{
  protected $model = \App\Models\Enterprise::class;

  public function definition(): array
  {
    return [
      'name' => fake()->company(),
      'ruc' => (string) fake()->numerify('###########'),
      'email' => fake()->companyEmail(),
      'phone' => fake()->numerify('#########'),
      'address' => fake()->address(),
      'contact_name' => fake()->name(),
      'website' => fake()->url(),
      'image' => null,
      'id_enterprise_types' => EnterpriseType::factory(),
      'cuid_inserted' => DB::raw('CUID_19D_B10()'),
    ];
  }
}
