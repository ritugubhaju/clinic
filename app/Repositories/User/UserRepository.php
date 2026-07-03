<?php

namespace App\Repositories\User;

use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public function paginate(int $perPage, int $page): array
    {
        $offset = ($page - 1) * $perPage;

        return DB::select(
            'SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?',
            [$perPage, $offset]
        );
    }

    public function total(): int
    {
        return DB::selectOne('SELECT COUNT(*) as count FROM users')->count;
    }

    public function find(int $id): ?object
    {
        return DB::selectOne(
            'SELECT id, name, email, role, created_at FROM users WHERE id = ?',
            [$id]
        );
    }

    public function create(array $data): int
    {
        DB::insert(
            'INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())',
            [$data['name'], $data['email'], $data['password'], $data['role']]
        );

        return (int) DB::getPdo()->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sets = [];
        $bindings = [];

        foreach (['name', 'email', 'role', 'password'] as $field) {
            if (isset($data[$field])) {
                $sets[] = "$field = ?";
                $bindings[] = $data[$field];
            }
        }

        if (empty($sets)) {
            return false;
        }

        $bindings[] = $id;

        return (bool) DB::update(
            'UPDATE users SET ' . implode(', ', $sets) . ', updated_at = NOW() WHERE id = ?',
            $bindings
        );
    }

    public function delete(int $id): bool
    {
        return (bool) DB::delete('DELETE FROM users WHERE id = ?', [$id]);
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        if ($excludeId) {
            $result = DB::selectOne(
                'SELECT COUNT(*) as count FROM users WHERE email = ? AND id != ?',
                [$email, $excludeId]
            );
        } else {
            $result = DB::selectOne(
                'SELECT COUNT(*) as count FROM users WHERE email = ?',
                [$email]
            );
        }

        return $result->count > 0;
    }

    public function findByEmail(string $email): ?object
    {
        return DB::selectOne('SELECT * FROM users WHERE email = ?', [$email]);
    }
}
