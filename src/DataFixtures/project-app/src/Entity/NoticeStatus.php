<?php

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
     * Zwraca wszystkie dost�pne statusy.
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
     * Zwraca status na podstawie warto�ci.
     */
    public static function from(string $statusId): ?string
    {
        $statuses = self::getAvailableStatuses();

        if (in_array($statusId, $statuses, true)) {
            return $statusId;
        }

        return null; // Zwraca null, je�li status jest nieznany
    }
}
