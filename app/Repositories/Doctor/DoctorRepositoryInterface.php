<?php

namespace App\Repositories\Doctor;

interface DoctorRepositoryInterface
{
    public function paginate(int $perPage, int $page): array;
    public function total(): int;
    public function find(int $id): ?object;
    public function create(array $data): int;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function licenseExists(string $license, ?int $excludeId = null): bool;
    public function all(): array;
    public function findByUserId(int $userId): ?object;
}
