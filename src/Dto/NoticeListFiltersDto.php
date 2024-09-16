<?php
namespace App\Dto;

use App\Entity\Category;
use App\Entity\NoticeStatus;
use App\Entity\Tag;

/**
 * Class NoticeListFiltersDto.
 */
class NoticeListFiltersDto
{
    private ?Category $category;
    private ?Tag $tag;
    private ?NoticeStatus $status;

    /**
     * Constructor.
     *
     * @param Category|null     $category
     * @param Tag|null          $tag
     * @param NoticeStatus|null $status
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
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Get tag.
     *
     * @return Tag|null
     */
    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    /**
     * Get status.
     *
     * @return NoticeStatus|null
     */
    public function getStatus(): ?NoticeStatus
    {
        return $this->status;
    }
}