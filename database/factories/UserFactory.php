<?php

namespace Database\Factories;

use App\Models\Enterprise;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  public function definition(): array
  {
    return [
      'fullname' => fake()->name(),
      'username' => substr(fake()->unique()->userName(), 0, 16),
      'password' => bcrypt('password'),
      'status' => 1,
      'id_enterprises' => Enterprise::factory(),
      'cuid_inserted' => DB::raw('CUID_19D_B10()'),
    ];
  }

  public function inactive(): static
  {
    return $this->state(fn () => [
      'cuid_deleted' => DB::raw('CUID_19D_B10()'),
    ]);
  }
}
