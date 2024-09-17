<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Dto;

/**
 * Class NoticeListInputFiltersDto.
 *
 * This DTO (Data Transfer Object) represents the input filters used for querying the list of notices.
 */
class NoticeListInputFiltersDto
{
    /**
     * Constructor.
     *
     * @param int|null $categoryId The ID of the category filter
     * @param int|null $tagId      The ID of the tag filter
     * @param int      $statusId   The ID of the status filter, defaults to 1 (active)
     */
    public function __construct(public readonly ?int $categoryId = null, public readonly ?int $tagId = null, public readonly int $statusId = 1)
    {
    }
}
