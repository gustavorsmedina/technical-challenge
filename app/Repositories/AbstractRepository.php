<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class AbstractRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function save(array $data): Model
    {
        return $this->model->create($data);
    }

    public function findById($id): ?Model
    {
        return $this->model->find($id);
    }

    public function findAll(array $params): LengthAwarePaginator
    {
        return $this->model->paginate($params['per_page'], ['*'], 'page', $params['page']);
    }

    public function update($id): Model
    {
        // TODO: Implement update() method.
    }

    public function delete($id): void
    {
        // TODO: Implement delete() method.
    }
}