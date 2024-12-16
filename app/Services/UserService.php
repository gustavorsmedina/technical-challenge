<?php

namespace App\Services;

use App\Exceptions\{InvalidDocumentException, UserAlreadyExistsException, UserNotFoundException};
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
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
            throw new InvalidDocumentException();
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

        $user = $this->userRepository->findWithWallet($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function updateUser(string $id, array $data): User
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        if (isset($data['document']) || isset($data['email'])) {
            $userExists = $this->userRepository->findByEmailOrDocument($data['email'], $data['document']);

            if ($userExists) {
                throw new UserAlreadyExistsException("The document or email already exists.");
            }
        }

        return $this->userRepository->update($id, $data);
    }

    public function deleteUser(string $id): void
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $this->userRepository->delete($id);
    }
}
