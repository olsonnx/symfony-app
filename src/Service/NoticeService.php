<?php
/**
 * Notice service.
 */

namespace App\Service;

use App\Dto\NoticeListFiltersDto;
use App\Dto\NoticeListInputFiltersDto;
use App\Entity\Notice;
use App\Entity\NoticeStatus;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\NoticeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class NoticeService.
 */
class NoticeService implements NoticeServiceInterface
{
    /**
     * Liczba elementĂłw na stronÄ™.
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * @var EntityManagerInterface Entity Manager do zarzÄ…dzania encjami
     */
    private EntityManagerInterface $entityManager;

    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService  Category service
     * @param PaginatorInterface       $paginator        Paginator
     * @param TagServiceInterface      $tagService       Tag service
     * @param NoticeRepository         $noticeRepository Notice repository
     * @param EntityManagerInterface   $entityManager    Entity Manager
     */
    public function __construct(
        private readonly CategoryServiceInterface $categoryService,
        private readonly PaginatorInterface $paginator,
        private readonly TagServiceInterface $tagService,
        private readonly NoticeRepository $noticeRepository,
        EntityManagerInterface $entityManager,
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * Prepare filters for the notices list.
     *
     * @param NoticeListInputFiltersDto $filters Raw filters from request
     *
     * @return NoticeListFiltersDto Result filters
     *
     * @throws NonUniqueResultException
     */
    private function prepareFilters(NoticeListInputFiltersDto $filters): NoticeListFiltersDto
    {
        return new NoticeListFiltersDto(
            null !== $filters->categoryId ? $this->categoryService->findOneById($filters->categoryId) : null,
            null !== $filters->tagId ? $this->tagService->findOneById($filters->tagId) : null,
            // Ustaw status jako null, jeĹ›li uĹĽytkownik ma rolÄ™ administratora (statusId = -1)
            -1 === $filters->statusId ? null : NoticeStatus::from($filters->statusId)
        );
    }

    /**
     * get paginated list.
     *
     * @throws NonUniqueResultException
     */
    public function getPaginatedList(int $page, ?User $author, NoticeListInputFiltersDto $filters): PaginationInterface
    {
        // PrzeksztaĹ‚Ä‡ NoticeListInputFiltersDto na NoticeListFiltersDto
        $filters = $this->prepareFilters($filters);

        // JeĹ›li admin, nie ograniczaj po autorze
        if ($author && in_array('ROLE_ADMIN', $author->getRoles())) {
            return $this->paginator->paginate(
                $this->noticeRepository->queryAll($filters),  // PrzekaĹĽ odpowiednie filtry
                $page,
                self::PAGINATOR_ITEMS_PER_PAGE
            );
        }

        // Inni uĹĽytkownicy widzÄ… tylko swoje ogĹ‚oszenia lub publiczne
        return $this->paginator->paginate(
            $this->noticeRepository->queryByAuthor($author, $filters),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get a single notice by ID.
     *
     * Pobiera pojedyncze ogĹ‚oszenie na podstawie ID.
     *
     * @param int $noticeId ID ogĹ‚oszenia
     *
     * @return Notice|null Zwraca obiekt ogĹ‚oszenia lub null, jeĹ›li nie znaleziono
     */
    public function getNotice(int $noticeId): ?Notice
    {
        return $this->noticeRepository->find($noticeId);
    }

    /**
     * Save entity.
     *
     * @param Notice $notice Notice entity
     */
    public function save(Notice $notice): void
    {
        $this->entityManager->persist($notice);
        $this->entityManager->flush();
    }

    /**
     * Delete a notice.
     *
     * Usuwa ogĹ‚oszenie.
     *
     * @param Notice $notice Encja ogĹ‚oszenia
     */
    public function delete(Notice $notice): void
    {
        $this->entityManager->remove($notice);
        $this->entityManager->flush();
    }

    /**
     * Can Notice be deleted?
     *
     * Sprawdza, czy ogĹ‚oszenie moĹĽe byÄ‡ usuniÄ™te.
     *
     * @param Notice $notice Notice entity
     *
     * @return bool Czy ogĹ‚oszenie moĹĽe byÄ‡ usuniÄ™te
     */
    public function canBeDeleted(Notice $notice): bool
    {
        // Tutaj moĹĽesz dodaÄ‡ dodatkowe logiki, np. czy sÄ… zaleĹĽnoĹ›ci, ktĂłre blokujÄ… usuniÄ™cie.
        return true;
    }

    /**
     * Find by title.
     *
     * @param string $title Tag title
     *
     * @return Tag|null Tag entity
     */
    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneByTitle($title);
    }
}
