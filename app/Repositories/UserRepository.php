<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends AbstractRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail($email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByEmailOrDocument($email, $document): ?User
    {
        return $this->model->where('email', $email)->orWhere('document', $document)->first();
    }

}
