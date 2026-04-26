<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserIsActiveTest extends TestCase
{
  use DatabaseTransactions;

  public function test_freshly_created_user_is_active(): void
  {
    $user = User::factory()->create();

    $this->assertTrue($user->isActive());
  }

  public function test_disable_marks_user_inactive(): void
  {
    $user = User::factory()->create();

    $user->disable();

    $this->assertFalse($user->fresh()->isActive());
    $this->assertNotNull($user->fresh()->cuid_deleted);
  }

  public function test_enable_restores_active_status(): void
  {
    $user = User::factory()->create();
    $user->disable();
    $this->assertFalse($user->fresh()->isActive());

    $user->fresh()->enable();

    $this->assertTrue($user->fresh()->isActive());
    $this->assertNull($user->fresh()->cuid_deleted);
  }
}
