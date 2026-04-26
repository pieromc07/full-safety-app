<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use App\Models\EnterpriseType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SmokeTest extends TestCase
{
  use DatabaseTransactions;

  public function test_test_database_is_active(): void
  {
    $this->assertSame('mysql_testing', config('database.default'));
  }

  public function test_seed_data_is_present(): void
  {
    $this->assertGreaterThan(0, EnterpriseType::count(), 'Falta seed: corre `make test-fresh`');
    $this->assertGreaterThan(0, Enterprise::count(), 'Falta seed: corre `make test-fresh`');
    $this->assertGreaterThan(0, User::count(), 'Falta seed: corre `make test-fresh`');
  }

  public function test_factories_can_create_full_chain(): void
  {
    $user = User::factory()->create();

    $this->assertNotNull($user->id_users);
    $this->assertNotNull($user->cuid_inserted);
    $this->assertNotNull($user->id_enterprises);

    $enterprise = Enterprise::find($user->id_enterprises);
    $this->assertNotNull($enterprise);
    $this->assertNotNull($enterprise->id_enterprise_types);

    $type = EnterpriseType::find($enterprise->id_enterprise_types);
    $this->assertNotNull($type);
  }

  public function test_cuid_function_is_available(): void
  {
    $row = \DB::selectOne('SELECT CUID_19D_B10() AS c');
    $this->assertNotEmpty($row->c);
  }
}
