<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function getPaginatedUsers(int $perPage = 15, int $page = 1): array
    {
        $users = $this->userRepository->paginate($perPage, $page);
        $total = $this->userRepository->total();

        return [
            'data'         => $users,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int) ceil($total / $perPage),
        ];
    }

    public function createUser(array $data): void
    {
        $data['password'] = Hash::make($data['password']);
        $userId = $this->userRepository->create($data);

        User::find($userId)->assignRole($data['role']);
    }

    public function updateUser(int $id, array $data): bool
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $result = $this->userRepository->update($id, $data);

        if ($result && isset($data['role'])) {
            User::find($id)->syncRoles([$data['role']]);
        }

        return $result;
    }

    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function findUser(int $id): ?object
    {
        return $this->userRepository->find($id);
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        return $this->userRepository->emailExists($email, $excludeId);
    }
}
