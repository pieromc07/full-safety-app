<?php

namespace Tests\Feature\Web;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthWebTest extends TestCase
{
  use DatabaseTransactions;

  public function test_login_form_is_accessible(): void
  {
    $this->get('/login')->assertOk();
  }

  public function test_master_can_login(): void
  {
    $resp = $this->post('/login', [
      'username' => 'master',
      'password' => 'M@ster2025!',
    ]);

    $resp->assertRedirect('/home');
    $this->assertAuthenticated();
  }

  public function test_login_fails_for_unknown_user(): void
  {
    $this->post('/login', [
      'username' => 'nobody',
      'password' => 'whatever1',
    ])
      ->assertRedirect()
      ->assertSessionHasErrors('username');

    $this->assertGuest();
  }

  public function test_login_fails_for_wrong_password(): void
  {
    $this->post('/login', [
      'username' => 'master',
      'password' => 'wrong-password',
    ])
      ->assertRedirect()
      ->assertSessionHasErrors('password');

    $this->assertGuest();
  }

  public function test_logout_ends_session(): void
  {
    $user = User::where('username', 'master')->firstOrFail();

    $this->actingAs($user)
      ->post('/logout')
      ->assertRedirect('/');

    $this->assertGuest();
  }
}
