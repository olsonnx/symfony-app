<?php

namespace App\Service;

use App\Entity\Tag;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Tag Service Interface
 */
interface TagServiceInterface
{
    /**
     * Find or create a tag by title.
     *
     * @param string $title
     * @return Tag
     */
    public function findOrCreate(string $title): Tag;

    /**
     * Get all tags.
     *
     * @return array
     */
    public function findAll(): array;

    /**
     * Find a tag by title.
     *
     * @param string $title
     * @return Tag|null
     */
    public function findOneByTitle(string $title): ?Tag;

    /**
     * Save a tag entity.
     *
     * @param Tag $tag
     */
    public function save(Tag $tag): void;

    /**
     * Delete a tag entity.
     *
     * @param Tag $tag
     */
    public function delete(Tag $tag): void;

    /**
     * Get paginated list of tags.
     *
     * @param int $page
     * @param string $sort
     * @param string $direction
     * @return PaginationInterface
     */
    public function getPaginatedList(int $page, string $sort, string $direction): PaginationInterface;
}
