<?php

namespace App\Services;

use App\Brokers\RabbitMQ;
use App\Enums\{TransactionStatus, UserStatus, UserType};
use App\Exceptions\{InsufficientBalanceException,
    MerchantTransactionNotAllowedException,
    SelfTransactionNotAllowedException,
    TransactionFailedException,
    TransactionNotFoundException,
    UnauthorizedTransactionException,
    UserNotActiveException,
    UserNotFoundException,
    WalletNotFoundException};
use App\Models\Transaction;
use App\Repositories\{TransactionRepository, UserRepository};
use Illuminate\Support\Facades\{DB, Log};

class TransactionService
{
    private TransactionRepository $transactionRepository;

    private UserRepository $userRepository;

    private AuthorizerService $authorizerService;

    private RabbitMQ $rabbitMQ;

    public function __construct(
        TransactionRepository $transactionRepository,
        UserRepository $userRepository,
        AuthorizerService $authorizerService,
        RabbitMQ $rabbitMQ
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->userRepository        = $userRepository;
        $this->authorizerService     = $authorizerService;
        $this->rabbitMQ              = $rabbitMQ;
    }

    public function createTransaction(array $data): Transaction
    {
        $payee = $this->userRepository->findById($data['payee']);
        $payer = $this->userRepository->findById($data['payer']);

        $this->validateUser($payer, $payee, $data['value']);

        $transaction = $this->transactionRepository->save($data);

        Log::info('Transaction created.', $transaction->toArray());

        $this->validateAuthorization($transaction);

        try {
            DB::transaction(function () use ($transaction, $payer, $payee, $data) {
                $payer->wallet->balance -= $data['value'];
                $payee->wallet->balance += $data['value'];

                $payer->wallet->save();
                $payee->wallet->save();

                $transaction->status = TransactionStatus::SUCCESS;
                $transaction->save();
            });

            $this->rabbitMQ->send('notifications', $data);
            Log::info('Transaction success. Sending notification...', $transaction->toArray());

        } catch (Exception $e) {
            Log::error('Transaction failed.', $transaction->toArray());
            $transaction->status = TransactionStatus::FAILED;
            $transaction->save();

            throw new TransactionFailedException();
        }

        return $transaction;
    }

    private function validateUser($payer, $payee, $value): void
    {
        if (!$payer || !$payee) {
            throw new UserNotFoundException('Payer or payee not found.');
        }

        if ($payer->id === $payee->id) {
            throw new SelfTransactionNotAllowedException('Self transaction not allowed.');
        }

        if ($payer->status === UserStatus::INACTIVE) {
            throw new UserNotActiveException('User is not active.');
        }

        if ($payee->status === UserStatus::INACTIVE) {
            throw new UserNotActiveException('User is not active.');
        }

        if ($payer->user_type === UserType::MERCHANT) {
            throw new MerchantTransactionNotAllowedException('Merchant cant make transactions.');
        }

        if (!$payer->wallet || !$payee->wallet) {
            throw new WalletNotFoundException('Payer or payee wallet not found.');
        }

        if ($payer->wallet->balance < $value) {
            throw new InsufficientBalanceException('Insufficient balance.');
        }
    }

    private function validateAuthorization(Transaction $transaction): void
    {
        $authorize = $this->authorizerService->authorize();

        if (!$authorize) {
            $transaction->status = TransactionStatus::UNAUTHORIZED;
            $transaction->save();

            throw new UnauthorizedTransactionException();
        }
    }

    public function getTransaction(string $id): Transaction
    {
        $transaction = $this->transactionRepository->findTransactionWithPayerAndPayee($id);

        if (!$transaction) {
            throw new TransactionNotFoundException();
        }

        return $transaction;
    }
}
