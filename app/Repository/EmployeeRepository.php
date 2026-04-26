<?php

namespace App\Repository;

use App\Models\Employee;

class EmployeeRepository extends BaseRepository
{
  public function __construct(Employee $model)
  {
    parent::__construct($model);
  }

  /**
   * Get employees by enterprise that don't have a user assigned
   */
  public function allByEnterpriseNotUsers($enterpriseId)
  {
    $query = Employee::whereNull('cuid_deleted')
      ->whereNull('user_id');

    if ($enterpriseId) {
      $query->where('id_transport_enterprises', $enterpriseId);
    }

    return $query->get();
  }

  /**
   * Check if employee already has a user
   */
  public function existUser($employeeId)
  {
    $employee = Employee::find($employeeId);
    return $employee && $employee->user_id !== null;
  }
}
