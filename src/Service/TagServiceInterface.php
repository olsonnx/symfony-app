<?php

namespace App\Service;

use App\Entity\Tag;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Tag Service Interface.
 */
interface TagServiceInterface
{
    /**
     * Find or create a tag by title.
     */
    public function findOrCreate(string $title): Tag;

    /**
     * Get all tags.
     */
    public function findAll(): array;

    /**
     * Find a tag by title.
     */
    public function findOneByTitle(string $title): ?Tag;

    /**
     * Save a tag entity.
     */
    public function save(Tag $tag): void;

    /**
     * Delete a tag entity.
     */
    public function delete(Tag $tag): void;

    /**
     * Get paginated list of tags.
     */
    public function getPaginatedList(int $page, string $sort, string $direction): PaginationInterface;
}
