<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exceptions\{UserAlreadyExistsException, UserNotFoundException};
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(array $data)
    {

        $document = preg_replace('/[^0-9]/', '', $data['document']);

        if (!in_array(strlen($document), [11, 14])) {
            throw new Exception('Invalid CPF/CNPJ.', 422);
        }

        $userExists = $this->userRepository->findByEmailOrDocument($data['email'], $document);

        if ($userExists) {
            throw new UserAlreadyExistsException();
        }

        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->save($data);
    }

    public function getAllUsers(array $params): LengthAwarePaginator
    {
        return $this->userRepository->findAll($params);
    }

    public function getUser(string $id): User
    {

        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
