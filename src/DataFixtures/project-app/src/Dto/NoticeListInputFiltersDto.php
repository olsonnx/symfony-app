<?php
/**
 * Notice list input filters DTO.
 */

namespace App\Dto;

/**
 * Class NoticeListInputFiltersDto.
 */
class NoticeListInputFiltersDto
{
    public function __construct(
        public readonly ?int $categoryId = null,
        public readonly ?int $tagId = null,
        public readonly int $statusId = 1,
    ) {
    }
}
