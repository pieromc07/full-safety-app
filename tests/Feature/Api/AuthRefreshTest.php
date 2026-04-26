<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\AuthenticatesApiUser;

class AuthRefreshTest extends TestCase
{
  use DatabaseTransactions, AuthenticatesApiUser;

  public function test_refresh_returns_new_token(): void
  {
    $user = $this->masterUser();
    $original = $this->tokenFor($user);

    $resp = $this->postJson('/api/refresh', [], [
      'Authorization' => 'Bearer ' . $original,
    ]);

    $resp->assertOk()
      ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);

    $this->assertNotSame($original, $resp->json('access_token'));
  }

  public function test_refresh_rejects_invalid_token(): void
  {
    $resp = $this->postJson('/api/refresh', [], [
      'Authorization' => 'Bearer garbage.token.here',
    ]);

    $resp->assertStatus(401);
  }
}
