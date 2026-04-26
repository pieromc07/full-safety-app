<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
  protected Model $model;
  protected ?int $enterpriseId = null;
  protected ?int $branchId = null;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function all()
  {
    return $this->model->whereNull('cuid_deleted')->get();
  }

  public function find($id)
  {
    return $this->model->find($id);
  }

  public function create(array $data)
  {
    return $this->model->create($data);
  }

  public function update($id, array $data)
  {
    $record = $this->model->find($id);
    if ($record) {
      $record->update($data);
    }
    return $record;
  }

  public function delete($id)
  {
    $record = $this->model->find($id);
    if ($record) {
      $record->delete();
    }
    return $record;
  }

  public function cuid_delete($id)
  {
    $pk = $this->model->getKeyName();
    DB::table($this->model->getTable())
      ->where($pk, $id)
      ->update(['cuid_deleted' => DB::raw('CUID_19D_B10()')]);
  }

  public function searchNotDeleted($column, $search, $take)
  {
    return $this->model
      ->whereNull('cuid_deleted')
      ->where($column, 'like', "%{$search}%")
      ->paginate($take);
  }

  public function searchMultipleByEnterprise(array $columns, $search, $take, $enterpriseId)
  {
    $query = $this->model->whereNull($this->model->getTable() . '.cuid_deleted');

    if ($enterpriseId) {
      $query->where($this->model->getTable() . '.id_enterprises', $enterpriseId);
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

  public function setEnterpriseId($request)
  {
    $this->enterpriseId = $request->session()->get('enterpriseId');
  }

  public function setBranchId($request)
  {
    $this->branchId = $request->session()->get('branchId');
  }
}
