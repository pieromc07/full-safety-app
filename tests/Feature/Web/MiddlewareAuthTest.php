<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MiddlewareAuthTest extends TestCase
{
  use DatabaseTransactions;

  public static function guardedRoutes(): array
  {
    return [
      ['/home'],
      ['/checkpoints'],
      ['/enterprises'],
      ['/evidences'],
      ['/categories'],
      ['/employees'],
      ['/inspections'],
      ['/products'],
      ['/users'],
      ['/roles'],
      ['/permissions'],
    ];
  }

  /**
   * @dataProvider guardedRoutes
   */
  public function test_guest_is_redirected_to_login(string $uri): void
  {
    $this->get($uri)->assertRedirect('/login');
  }
}
