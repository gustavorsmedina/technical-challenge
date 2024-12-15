<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function save(array $data): Model;

    public function findById($id): ?Model;

    public function findAll(array $params): LengthAwarePaginator;

    public function update($id, $data): Model;

    public function delete($id): void;

}
