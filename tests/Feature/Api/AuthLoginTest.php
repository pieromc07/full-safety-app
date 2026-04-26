<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
  use DatabaseTransactions;

  protected function setUp(): void
  {
    parent::setUp();
    RateLimiter::clear('login');
  }

  public function test_login_succeeds_with_master_user(): void
  {
    $resp = $this->postJson('/api/login', [
      'username' => 'master',
      'password' => 'M@ster2025!',
    ]);

    $resp->assertOk()
      ->assertJsonStructure([
        'status', 'message', 'access_token', 'token_type', 'expires_in',
        'user' => ['id', 'fullname', 'username', 'status'],
      ])
      ->assertJson([
        'status' => true,
        'token_type' => 'bearer',
        'user' => ['username' => 'master'],
      ]);
  }

  public function test_login_fails_with_invalid_credentials(): void
  {
    $resp = $this->postJson('/api/login', [
      'username' => 'master',
      'password' => 'wrong-password',
    ]);

    $resp->assertStatus(401)
      ->assertJson(['status' => false, 'message' => 'Datos incorrectos']);
  }

  public function test_login_fails_for_inactive_user(): void
  {
    $user = User::factory()->create([
      'username' => 'inactive_u',
      'password' => 'Secret123!',
    ]);
    $user->cuid_deleted = DB::raw('CUID_19D_B10()');
    $user->save();

    $resp = $this->postJson('/api/login', [
      'username' => 'inactive_u',
      'password' => 'Secret123!',
    ]);

    $resp->assertStatus(401)
      ->assertJson(['status' => false, 'message' => 'Usuario inactivo']);
  }

  public function test_login_is_throttled_after_five_failed_attempts(): void
  {
    for ($i = 0; $i < 5; $i++) {
      $this->postJson('/api/login', [
        'username' => 'master',
        'password' => 'wrong',
      ])->assertStatus(401);
    }

    $this->postJson('/api/login', [
      'username' => 'master',
      'password' => 'wrong',
    ])->assertStatus(429);
  }
}
