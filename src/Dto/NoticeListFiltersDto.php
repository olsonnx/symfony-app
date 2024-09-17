<?php
/**
 * Notice management app
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Dto;

use App\Entity\Category;
use App\Entity\NoticeStatus;
use App\Entity\Tag;

/**
 * Class NoticeListFiltersDto.
 *
 * This class represents the filters used for querying notice lists.
 */
class NoticeListFiltersDto
{
    private ?Category $category;
    private ?Tag $tag;
    private ?NoticeStatus $status;

    /**
     * Constructor.
     *
     * @param Category|null     $category The category filter
     * @param Tag|null          $tag      The tag filter
     * @param NoticeStatus|null $status   The status filter
     */
    public function __construct(?Category $category, ?Tag $tag, ?NoticeStatus $status)
    {
        $this->category = $category;
        $this->tag = $tag;
        $this->status = $status;
    }

    /**
     * Get category.
     *
     * @return Category|null The category entity or null if not set
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Get tag.
     *
     * @return Tag|null The tag entity or null if not set
     */
    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    /**
     * Get status.
     *
     * @return NoticeStatus|null The notice status entity or null if not set
     */
    public function getStatus(): ?NoticeStatus
    {
        return $this->status;
    }
}
