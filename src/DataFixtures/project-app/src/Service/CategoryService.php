<?php
/**
 * Category service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\NoticeRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CategoryService.
 *
 * Serwis obsługujący logikę związaną z kategoriami.
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * Items per page.
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * @var EntityManagerInterface Entity Manager do zarządzania encjami
     */
    private EntityManagerInterface $entityManager;

    /**
     * Constructor.
     *
     * @param CategoryRepository     $categoryRepository Repozytorium kategorii
     * @param NoticeRepository       $noticeRepository   Repozytorium ogłoszeń
     * @param PaginatorInterface     $paginator          Paginator
     * @param EntityManagerInterface $entityManager      Manager encji Doctrine
     */
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly NoticeRepository $noticeRepository,
        private readonly PaginatorInterface $paginator,
        EntityManagerInterface $entityManager,  // Wstrzyknięcie EntityManager
    ) {
        $this->entityManager = $entityManager;  // Przypisanie entity managera
    }

    /**
     * Get paginated list of categories.
     *
     * Pobiera paginowaną listę kategorii.
     *
     * @param int $page Numer strony
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->categoryRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get category with related notices.
     *
     * Pobiera kategorię wraz z powiązanymi ogłoszeniami.
     *
     * @param int $categoryId ID kategorii
     *
     * @return array|null Zwraca tablicę z kategorią i jej ogłoszeniami lub null, jeśli nie znaleziono
     */
    public function getCategoryWithNotices(int $categoryId): ?array
    {
        $category = $this->categoryRepository->find($categoryId);

        if (!$category) {
            return null;
        }

        // Nawet jeśli nie ma ogłoszeń, zwracamy pustą tablicę zamiast nulla
        $notices = $this->noticeRepository->findByCategory($category);

        return [
            'category' => $category,
            'notices' => $notices ?? [], // Zabezpieczenie na wypadek braku ogłoszeń
        ];
    }

    /**
     * Save entity.
     *
     * @param Category $category Category entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Category $category): void
    {

        $this->categoryRepository->save($category);
    }

    /**
     * Delete a category.
     *
     * @param Category $category Encja kategorii
     */
    public function delete(Category $category): void
    {
        // Użyj EntityManager do usunięcia encji
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }

    /**
     * Can Category be deleted?
     *
     * @param Category $category Category entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Category $category): bool
    {
        try {
            $result = $this->noticeRepository->countByCategory($category);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }

    /**
     * Find by id.
     *
     * @param int $id Category id
     *
     * @return Category|null Category entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Category
    {
        return $this->categoryRepository->findOneById($id);
    }
}
