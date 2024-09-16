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
     * Zwraca wszystkie dostêpne statusy.
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
     * Zwraca status na podstawie wartoœci.
     */
    public static function from(string $statusId): ?string
    {
        $statuses = self::getAvailableStatuses();

        if (in_array($statusId, $statuses, true)) {
            return $statusId;
        }

        return null; // Zwraca null, jeœli status jest nieznany
    }
}
