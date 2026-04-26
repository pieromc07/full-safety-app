<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\AuthenticatesApiUser;

class AuthMeTest extends TestCase
{
  use DatabaseTransactions, AuthenticatesApiUser;

  public function test_me_returns_user_when_authenticated(): void
  {
    $user = $this->masterUser();

    $resp = $this->postJson('/api/me', [], $this->bearer($user));

    $resp->assertOk()
      ->assertJson([
        'username' => 'master',
        'id_users' => $user->id_users,
      ]);
  }

  public function test_me_rejects_request_without_token(): void
  {
    $resp = $this->postJson('/api/me');

    $resp->assertStatus(401)
      ->assertJsonStructure(['status', 'error', 'message']);
  }

  public function test_me_rejects_invalid_token(): void
  {
    $resp = $this->postJson('/api/me', [], [
      'Authorization' => 'Bearer not-a-real-token',
    ]);

    $resp->assertStatus(401)
      ->assertJson(['status' => false]);
  }
}
