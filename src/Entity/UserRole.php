<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Entity;

/**
 * User Role.
 */
class UserRole
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * Get all possible role values.
     *
     * @return array Available role values
     */
    public static function getAvailableRoles(): array
    {
        return [
            self::ROLE_USER,
            self::ROLE_ADMIN,
        ];
    }

    /**
     * Get the role label.
     *
     * @param string $role Role
     *
     * @return string Role label
     */
    public static function label(string $role): string
    {
        return match ($role) {
            self::ROLE_USER => 'label.role_user',
            self::ROLE_ADMIN => 'label.role_admin',
            default => 'Nieznana rola',
        };
    }
}
