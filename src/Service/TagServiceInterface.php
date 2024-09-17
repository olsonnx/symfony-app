<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

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
     *
     * @param string $title The title of the tag
     *
     * @return Tag The found or newly created tag
     */
    public function findOrCreate(string $title): Tag;

    /**
     * Get all tags.
     *
     * @return array The array of all tags
     */
    public function findAll(): array;

    /**
     * Find a tag by title.
     *
     * @param string $title The title of the tag
     *
     * @return Tag|null The found tag or null if not found
     */
    public function findOneByTitle(string $title): ?Tag;

    /**
     * Save a tag entity.
     *
     * @param Tag $tag The tag entity to save
     */
    public function save(Tag $tag): void;

    /**
     * Delete a tag entity.
     *
     * @param Tag $tag The tag entity to delete
     */
    public function delete(Tag $tag): void;

    /**
     * Get paginated list of tags.
     *
     * @param int    $page      The page number
     * @param string $sort      The field to sort by
     * @param string $direction The sort direction (ASC or DESC)
     *
     * @return PaginationInterface The paginated list of tags
     */
    public function getPaginatedList(int $page, string $sort, string $direction): PaginationInterface;
}
