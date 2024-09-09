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
    /**
     * @param int|null $categoryId
     * @param int|null $tagId
     * @param int $statusId
     */
    public function __construct(
        public readonly ?int $categoryId = null,
        public readonly ?int $tagId = null,
        public readonly int $statusId = 1
    ) {
    }
}