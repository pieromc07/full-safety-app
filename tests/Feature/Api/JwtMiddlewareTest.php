<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\AuthenticatesApiUser;

class JwtMiddlewareTest extends TestCase
{
  use DatabaseTransactions, AuthenticatesApiUser;

  public static function protectedEndpoints(): array
  {
    return [
      'GET /api/sync' => ['get', '/api/sync'],
      'POST /api/me' => ['post', '/api/me'],
      'POST /api/logout' => ['post', '/api/logout'],
      'POST /api/refresh' => ['post', '/api/refresh'],
      'POST /api/ping' => ['post', '/api/ping'],
      'POST /api/inspection' => ['post', '/api/inspection'],
      'POST /api/inspections/massive' => ['post', '/api/inspections/massive'],
      'POST /api/dialogue' => ['post', '/api/dialogue'],
      'POST /api/pauseactive' => ['post', '/api/pauseactive'],
      'POST /api/alcoholtest' => ['post', '/api/alcoholtest'],
      'POST /api/controlgps' => ['post', '/api/controlgps'],
    ];
  }

  /**
   * @dataProvider protectedEndpoints
   */
  public function test_protected_endpoint_rejects_request_without_token(string $method, string $uri): void
  {
    $resp = $this->json($method, $uri);

    $resp->assertStatus(401)
      ->assertJsonStructure(['status', 'error', 'message']);
  }

  public function test_ping_works_with_valid_token(): void
  {
    $resp = $this->postJson('/api/ping', [], $this->bearer($this->masterUser()));

    $resp->assertOk()->assertJson(['message' => 'pong']);
  }
}
