<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\AuthenticatesApiUser;

class AuthLogoutTest extends TestCase
{
  use DatabaseTransactions, AuthenticatesApiUser;

  public function test_logout_invalidates_token(): void
  {
    $user = $this->masterUser();
    $headers = $this->bearer($user);

    $this->postJson('/api/me', [], $headers)->assertOk();

    $this->postJson('/api/logout', [], $headers)
      ->assertOk()
      ->assertJson(['status' => true]);

    $this->postJson('/api/me', [], $headers)->assertStatus(401);
  }

  public function test_logout_responds_ok_even_without_token(): void
  {
    $resp = $this->postJson('/api/logout');

    $resp->assertStatus(401);
  }
}
