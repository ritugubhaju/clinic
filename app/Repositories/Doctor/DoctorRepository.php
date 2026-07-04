<?php

namespace App\Repositories\Doctor;

use Illuminate\Support\Facades\DB;

class DoctorRepository implements DoctorRepositoryInterface
{
    public function paginate(int $perPage, int $page): array
    {
        $offset = ($page - 1) * $perPage;

        return DB::select(
            'SELECT d.id, d.name, d.specialization, d.license_no, d.created_at
             FROM doctors d
             ORDER BY d.created_at DESC
             LIMIT ? OFFSET ?',
            [$perPage, $offset]
        );
    }

    public function total(): int
    {
        return DB::selectOne('SELECT COUNT(*) as count FROM doctors')->count;
    }

    public function find(int $id): ?object
    {
        return DB::selectOne(
            'SELECT d.id, d.name, d.specialization, d.license_no, d.created_at
             FROM doctors d
             WHERE d.id = ?',
            [$id]
        );
    }

    public function create(array $data): int
    {
        DB::insert(
            'INSERT INTO doctors (name, specialization, license_no, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())',
            [$data['name'], $data['specialization'], $data['license_no']]
        );

        return (int) DB::getPdo()->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        return (bool) DB::update(
            'UPDATE doctors SET name = ?, specialization = ?, license_no = ?, updated_at = NOW() WHERE id = ?',
            [$data['name'], $data['specialization'], $data['license_no'], $id]
        );
    }

    public function delete(int $id): bool
    {
        return (bool) DB::delete('DELETE FROM doctors WHERE id = ?', [$id]);
    }

    public function licenseExists(string $license, ?int $excludeId = null): bool
    {
        if ($excludeId) {
            $result = DB::selectOne(
                'SELECT COUNT(*) as count FROM doctors WHERE license_no = ? AND id != ?',
                [$license, $excludeId]
            );
        } else {
            $result = DB::selectOne(
                'SELECT COUNT(*) as count FROM doctors WHERE license_no = ?',
                [$license]
            );
        }

        return $result->count > 0;
    }

    public function all(): array
    {
        return DB::select('SELECT id, name, specialization, license_no FROM doctors ORDER BY name ASC');
    }

    public function findByUserId(int $userId): ?object
    {
        return DB::selectOne('SELECT * FROM doctors WHERE user_id = ?', [$userId]);
    }
}
