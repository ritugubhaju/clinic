<?php

namespace App\Repositories\Appointment;

interface AppointmentRepositoryInterface
{
    public function paginateByDoctor(int $doctorId, int $perPage, int $page): array;
    public function totalByDoctor(int $doctorId): int;
    public function find(int $id): ?object;
    public function create(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
