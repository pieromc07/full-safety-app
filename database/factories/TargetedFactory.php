<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Targeted>
 */
class TargetedFactory extends Factory
{
  protected $model = \App\Models\Targeted::class;

  public function definition(): array
  {
    return [
      'name' => fake()->unique()->words(2, true),
      'image' => null,
      'targeted_id' => null,
      'cuid_inserted' => DB::raw('CUID_19D_B10()'),
    ];
  }

  public function childOf(\App\Models\Targeted $parent): static
  {
    return $this->state(fn () => ['targeted_id' => $parent->id_targeteds]);
  }
}
