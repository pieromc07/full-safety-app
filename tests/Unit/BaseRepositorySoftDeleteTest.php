<?php

namespace Tests\Unit;

use App\Models\Enterprise;
use App\Repository\EnterpriseRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BaseRepositorySoftDeleteTest extends TestCase
{
  use DatabaseTransactions;

  public function test_all_excludes_soft_deleted_records(): void
  {
    $alive = Enterprise::factory()->create();
    $dead = Enterprise::factory()->create();
    DB::table('enterprises')
      ->where('id_enterprises', $dead->id_enterprises)
      ->update(['cuid_deleted' => DB::raw('CUID_19D_B10()')]);

    $repo = new EnterpriseRepository(new Enterprise());
    $ids = $repo->all()->pluck('id_enterprises')->all();

    $this->assertContains($alive->id_enterprises, $ids);
    $this->assertNotContains($dead->id_enterprises, $ids);
  }

  public function test_cuid_delete_marks_record_as_deleted(): void
  {
    $enterprise = Enterprise::factory()->create();
    $this->assertNull($enterprise->cuid_deleted);

    $repo = new EnterpriseRepository(new Enterprise());
    $repo->cuid_delete($enterprise->id_enterprises);

    $this->assertNotNull($enterprise->fresh()->cuid_deleted);
  }
}
