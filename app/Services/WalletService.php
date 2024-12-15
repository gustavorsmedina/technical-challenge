<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Exceptions\{UserAlreadyHasWalletException, UserNotActiveException, UserNotFoundException};
use App\Models\{Wallet};
use App\Repositories\{UserRepository, WalletRepository};

class WalletService
{
    private WalletRepository $walletRepository;

    private UserRepository $userRepository;

    public function __construct(WalletRepository $walletRepository, UserRepository $userRepository)
    {
        $this->walletRepository = $walletRepository;
        $this->userRepository   = $userRepository;
    }

    public function createWallet(array $data): Wallet
    {

        $user = $this->userRepository->findById($data['user_id']);

        if (!$user) {
            throw new UserNotFoundException();
        }

        if ($user->status == UserStatus::INACTIVE) {
            throw new UserNotActiveException();
        }

        $userHasWallet = $this->walletRepository->findByUserId($data['user_id']);

        if ($userHasWallet) {
            throw new UserAlreadyHasWalletException();
        }

        return $this->walletRepository->save($data);
    }

}
