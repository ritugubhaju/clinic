<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function paginate(int $perPage, int $page): array;
    public function total(): int;
    public function find(int $id): ?object;
    public function create(array $data): int;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function emailExists(string $email, ?int $excludeId = null): bool;
    public function findByEmail(string $email): ?object;
}
