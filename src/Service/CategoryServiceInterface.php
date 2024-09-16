<?php
/**
 * Category service interface.
 */

namespace App\Service;

use App\Entity\Category;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CategoryServiceInterface.
 */
interface CategoryServiceInterface
{
    /**
     * Get paginated list of categories.
     *
     * @param int $page Numer strony
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Get category with related notices.
     *
     * @param int $categoryId ID kategorii
     *
     * @return array|null Zwraca tablicÄ™ z kategoriÄ… i jej ogĹ‚oszeniami lub null, jeĹ›li nie znaleziono
     */
    public function getCategoryWithNotices(int $categoryId): ?array;

    /**
     * Save a category.
     *
     * @param Category $category Encja kategorii
     */
    public function save(Category $category): void;

    /**
     * Delete a category.
     *
     * @param Category $category Encja kategorii
     */
    public function delete(Category $category): void;

    /**
     * Can Category be deleted?
     *
     * @param Category $category Category entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Category $category): bool;
}
