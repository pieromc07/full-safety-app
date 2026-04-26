<?php

namespace Tests\Traits;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

trait AuthenticatesApiUser
{
  protected function tokenFor(User $user): string
  {
    return JWTAuth::fromUser($user);
  }

  protected function bearer(User $user): array
  {
    return ['Authorization' => 'Bearer ' . $this->tokenFor($user)];
  }

  protected function masterUser(): User
  {
    return User::where('username', 'master')->firstOrFail();
  }
}
