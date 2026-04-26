<?php

namespace App\Repository;

use App\Models\User;

class UserRepository extends BaseRepository
{
  public function __construct(User $model)
  {
    parent::__construct($model);
  }

  /**
   * Search users joining employees table, filtered by enterprise
   */
  public function customSearchMultipleByEnterprise(array $columns, $search, $take, $enterpriseId)
  {
    $query = User::query()
      ->leftJoin('employees', 'employees.user_id', '=', 'users.id_users')
      ->whereNull('users.cuid_deleted')
      ->select('users.*');

    if ($enterpriseId) {
      $query->where('users.id_enterprises', $enterpriseId);
    }

    if ($search) {
      $query->where(function ($q) use ($columns, $search) {
        foreach ($columns as $column) {
          $q->orWhere($column, 'like', "%{$search}%");
        }
      });
    }

    return $query->paginate($take);
  }
}
