<?php

namespace App\Repositories;

use App\Models\{Wallet};

class WalletRepository extends AbstractRepository
{
    public function __construct(Wallet $model)
    {
        parent::__construct($model);
    }

    public function findByUserId($userId): ?Wallet
    {
        return $this->model->where('user_id', $userId)->first();
    }

    public function findWalletWithUser($id): ?Wallet
    {
        $wallet = Wallet::with('user')->find($id);

        return $wallet;
    }

}
