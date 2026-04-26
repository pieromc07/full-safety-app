<?php

namespace Tests\Traits;

use App\Models\User;

trait AuthenticatesWebUser
{
  protected function loginAsMaster(): User
  {
    $master = User::where('username', 'master')->firstOrFail();
    $this->actingAs($master);
    return $master;
  }
}
