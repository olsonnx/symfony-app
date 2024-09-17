<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Entity;

/**
 * Notice Status.
 */
class NoticeStatus
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_ARCHIVED = 'archived';

    /**
     * Zwraca wszystkie dostępne statusy.
     *
     * @return array Lista dostępnych statusów
     */
    public static function getAvailableStatuses(): array
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_INACTIVE,
            self::STATUS_ARCHIVED,
        ];
    }

    /**
     * Zwraca status na podstawie wartości.
     *
     * @param string $statusId Identyfikator statusu
     *
     * @return string|null Status lub null, jeśli status nie istnieje
     */
    public static function from(string $statusId): ?string
    {
        $statuses = self::getAvailableStatuses();

        if (in_array($statusId, $statuses, true)) {
            return $statusId;
        }

        return null; // Zwraca null, jeśli status jest nieznany
    }
}
