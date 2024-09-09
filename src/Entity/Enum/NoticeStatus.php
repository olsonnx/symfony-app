<?php

namespace App\Entity\Enum;

/**
 *
 */
enum NoticeStatus: string
{
    case STATUS_ACTIVE = 'active';
    case STATUS_INACTIVE = 'inactive';
    case STATUS_ARCHIVED = 'archived';

    case UNKNOWN = '';

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::STATUS_ACTIVE => 'Aktywne',
            self::STATUS_INACTIVE => 'Nieaktywne',
            self::STATUS_ARCHIVED => 'Zarchiwizowane',


        };
    }
}
