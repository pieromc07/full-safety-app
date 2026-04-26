<?php

namespace App\Repository;

use App\Models\Unit;

class UnitRepository extends BaseRepository
{
  public function __construct(Unit $model)
  {
    parent::__construct($model);
  }
}
