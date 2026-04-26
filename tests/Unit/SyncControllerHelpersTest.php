<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\SyncController;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class SyncControllerHelpersTest extends TestCase
{
  private function invokePrivate(SyncController $obj, string $method, array $args)
  {
    $ref = new ReflectionClass($obj);
    $m = $ref->getMethod($method);
    $m->setAccessible(true);
    return $m->invokeArgs($obj, $args);
  }

  public function test_parse_date_accepts_dmy_format(): void
  {
    $controller = new SyncController();

    $result = $this->invokePrivate($controller, 'parseDate', ['15/05/2026']);

    $this->assertSame('2026-05-15', $result);
  }

  public function test_parse_date_rejects_invalid_format(): void
  {
    $controller = new SyncController();

    $this->expectException(\InvalidArgumentException::class);
    $this->invokePrivate($controller, 'parseDate', ['2026-05-15']);
  }

  public function test_parse_date_rejects_invalid_day(): void
  {
    $controller = new SyncController();

    $this->expectException(\InvalidArgumentException::class);
    $this->invokePrivate($controller, 'parseDate', ['32/05/2026']);
  }

  public function test_validate_required_passes_when_all_fields_present(): void
  {
    $controller = new SyncController();

    $this->invokePrivate($controller, 'validateRequired', [
      ['a' => 1, 'b' => 'x'],
      ['a', 'b'],
      'context',
    ]);

    $this->assertTrue(true); // no exception thrown
  }

  public function test_validate_required_throws_when_field_is_missing(): void
  {
    $controller = new SyncController();

    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessageMatches('/Campos requeridos faltantes en context.*b/');
    $this->invokePrivate($controller, 'validateRequired', [
      ['a' => 1],
      ['a', 'b'],
      'context',
    ]);
  }

  public function test_validate_required_throws_when_field_is_null(): void
  {
    $controller = new SyncController();

    $this->expectException(\InvalidArgumentException::class);
    $this->invokePrivate($controller, 'validateRequired', [
      ['a' => 1, 'b' => null],
      ['a', 'b'],
      'context',
    ]);
  }
}
