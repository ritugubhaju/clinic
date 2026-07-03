<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case RECEPTIONIST = 'receptionist';
    case DOCTOR = 'doctor';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::RECEPTIONIST => 'Receptionist',
            self::DOCTOR => 'Doctor',
        };
    }
}
