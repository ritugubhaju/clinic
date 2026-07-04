<?php

namespace App\Repositories\Appointment;

use Illuminate\Support\Facades\DB;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function paginateByDoctor(int $doctorId, int $perPage, int $page): array
    {
        $offset = ($page - 1) * $perPage;

        return DB::select(
            'SELECT * FROM appointments WHERE doctor_id = ? ORDER BY scheduled_at DESC LIMIT ? OFFSET ?',
            [$doctorId, $perPage, $offset]
        );
    }

    public function totalByDoctor(int $doctorId): int
    {
        return DB::selectOne(
            'SELECT COUNT(*) as count FROM appointments WHERE doctor_id = ?',
            [$doctorId]
        )->count;
    }

    public function find(int $id): ?object
    {
        return DB::selectOne('SELECT * FROM appointments WHERE id = ?', [$id]);
    }

    public function create(array $data): bool
    {
        return DB::insert(
            'INSERT INTO appointments (doctor_id, patient_name, scheduled_at, status, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())',
            [$data['doctor_id'], $data['patient_name'], $data['scheduled_at'], $data['status']]
        );
    }

    public function update(int $id, array $data): bool
    {
        return (bool) DB::update(
            'UPDATE appointments SET patient_name = ?, scheduled_at = ?, status = ?, updated_at = NOW() WHERE id = ?',
            [$data['patient_name'], $data['scheduled_at'], $data['status'], $id]
        );
    }

    public function delete(int $id): bool
    {
        return (bool) DB::delete('DELETE FROM appointments WHERE id = ?', [$id]);
    }
}
