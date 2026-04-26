<?php

namespace Tests\Feature\Web;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SidebarRoutesSmokeTest extends TestCase
{
  use DatabaseTransactions;

  public function test_no_named_get_route_returns_500_for_master(): void
  {
    $master = User::where('username', 'master')->firstOrFail();
    $this->actingAs($master);

    $failures = [];
    foreach (Route::getRoutes() as $route) {
      if (!in_array('GET', $route->methods(), true)) continue;
      if (!$route->getName()) continue;
      if (!in_array('web', $route->gatherMiddleware(), true)) continue;
      if (str_contains($route->uri(), '{')) continue;
      if (str_starts_with($route->uri(), '_')) continue;

      $uri = '/' . ltrim($route->uri(), '/');
      $status = $this->get($uri)->getStatusCode();

      if ($status >= 500) {
        $failures[] = "$uri → $status";
      }
    }

    $this->assertEmpty(
      $failures,
      "Rutas con error 5xx:\n" . implode("\n", $failures)
    );
  }

  public static function deadCreateRoutes(): array
  {
    return [
      ['checkpoint.create'],
      ['enterprisetype.create'],
      ['inspectiontype.create'],
      ['enterprise.create'],
      ['targeted.create'],
      ['category.create'],
      ['evidences.create'],
      ['inspections.create'],
      ['employee.create'],
      ['dialogues.create'],
      ['actives.create'],
      ['tests.create'],
      ['controls.create'],
      ['products.create'],
      ['productenterprises.create'],
    ];
  }

  /**
   * @dataProvider deadCreateRoutes
   */
  public function test_zombie_create_route_was_removed(string $name): void
  {
    $this->assertFalse(
      Route::has($name),
      "La ruta '{$name}' debería haber sido eliminada (apuntaba a una vista inexistente)."
    );
  }
}
