<?php

namespace Database\Factories;

use App\Models\Enterprise;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
  protected $model = \App\Models\Employee::class;

  public function definition(): array
  {
    $first = fake()->firstName();
    $last = fake()->lastName();
    return [
      'document' => (string) fake()->unique()->numerify('########'),
      'name' => $first,
      'lastname' => $last,
      'fullname' => "$first $last",
      'id_transport_enterprises' => Enterprise::factory(),
      'id_users' => null,
      'id_targeteds' => null,
      'job_title' => fake()->jobTitle(),
      'cuid_inserted' => DB::raw('CUID_19D_B10()'),
    ];
  }
}
