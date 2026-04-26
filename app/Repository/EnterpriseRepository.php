<?php

namespace App\Repository;

use App\Models\Enterprise;

class EnterpriseRepository extends BaseRepository
{
  public function __construct(Enterprise $model)
  {
    parent::__construct($model);
  }
}
