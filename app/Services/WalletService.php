<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Exceptions\{InsufficientBalanceException,
    UserAlreadyHasWalletException,
    UserNotActiveException,
    UserNotFoundException,
    WalletNotFoundException};
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

    public function getWallet(string $id): Wallet
    {
        $wallet = $this->walletRepository->findWalletWithUser($id);

        if (!$wallet) {
            throw new WalletNotFoundException();
        }

        return $wallet;
    }

    public function creditWallet(array $data): Wallet
    {
        $wallet = $this->walletRepository->findWalletWithUser($data['wallet_id']);

        if (!$wallet) {
            throw new WalletNotFoundException();
        }

        if ($wallet->user->status == UserStatus::INACTIVE) {
            throw new UserNotActiveException();
        }

        $this->walletRepository->update($wallet->id, [
            'balance'    => $wallet->balance += $data['value'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $wallet;
    }

    public function debitWallet(array $data): Wallet
    {
        $wallet = $this->walletRepository->findWalletWithUser($data['wallet_id']);

        if (!$wallet) {
            throw new WalletNotFoundException();
        }

        if ($wallet->user->status == UserStatus::INACTIVE) {
            throw new UserNotActiveException();
        }

        if ($wallet->balance < $data['value']) {
            throw new InsufficientBalanceException();
        }

        $this->walletRepository->update($wallet->id, [
            'balance'    => $wallet->balance -= $data['value'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $wallet;
    }

}
