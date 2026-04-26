<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\AuthenticatesWebUser;

class InspectionCrudTest extends TestCase
{
  use DatabaseTransactions, AuthenticatesWebUser;

  protected function setUp(): void
  {
    parent::setUp();
    $this->loginAsMaster();
  }

  public function test_operative_index_renders(): void
  {
    $this->get(route('inspections', ['type' => 1]))->assertOk();
  }

  public function test_documentary_index_renders(): void
  {
    $this->get(route('inspections', ['type' => 2]))->assertOk();
  }

  public function test_index_defaults_to_operative_when_no_type(): void
  {
    $this->get(route('inspections'))->assertOk();
  }
}
