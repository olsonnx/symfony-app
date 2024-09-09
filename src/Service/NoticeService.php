<?php
/**
 * Notice service.
 */

namespace App\Service;

use App\Entity\Notice;
use App\Dto\NoticeListInputFiltersDto;
use App\Entity\Tag;
use App\Entity\User;
use App\Dto\NoticeListFiltersDto;
use App\Entity\Enum\NoticeStatus;
use App\Repository\NoticeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TagRepository;

/**
 * Class NoticeService.
 */
class NoticeService implements NoticeServiceInterface
{
    /**
     * Liczba elementów na stronę.
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
     * @param CategoryServiceInterface $categoryService   Category service
     * @param PaginatorInterface       $paginator         Paginator
     * @param TagServiceInterface      $tagService        Tag service
     * @param NoticeRepository         $noticeRepository  Notice repository
     * @param EntityManagerInterface   $entityManager     Entity Manager
     */
    public function __construct(
        private readonly CategoryServiceInterface $categoryService,
        private readonly PaginatorInterface $paginator,
        private readonly TagServiceInterface $tagService,
        private readonly NoticeRepository $noticeRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * Prepare filters for the notices list.
     *
     * @param NoticeListInputFiltersDto $filters Raw filters from request
     *
     * @return NoticeListFiltersDto Result filters
     */
    private function prepareFilters(NoticeListInputFiltersDto $filters): NoticeListFiltersDto
    {
        return new NoticeListFiltersDto(
            null !== $filters->categoryId ? $this->categoryService->findOneById($filters->categoryId) : null,
            null !== $filters->tagId ? $this->tagService->findOneById($filters->tagId) : null,
            // Ustaw status jako null, jeśli użytkownik ma rolę administratora (statusId = -1)
            $filters->statusId === -1 ? null : NoticeStatus::tryFrom($filters->statusId)
        );
    }

    public function getPaginatedList(int $page, ?User $author, NoticeListInputFiltersDto $filters): PaginationInterface
    {
        // Przekształć NoticeListInputFiltersDto na NoticeListFiltersDto
        $filters = $this->prepareFilters($filters);

        // Jeśli admin, nie ograniczaj po autorze
        if ($author && in_array('ROLE_ADMIN', $author->getRoles())) {
            return $this->paginator->paginate(
                $this->noticeRepository->queryAll($filters),  // Przekaż odpowiednie filtry
                $page,
                self::PAGINATOR_ITEMS_PER_PAGE
            );
        }

        // Inni użytkownicy widzą tylko swoje ogłoszenia lub publiczne
        return $this->paginator->paginate(
            $this->noticeRepository->queryByAuthor($author, $filters),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }


    /**
     * Get a single notice by ID.
     *
     * Pobiera pojedyncze ogłoszenie na podstawie ID.
     *
     * @param int $noticeId ID ogłoszenia
     *
     * @return Notice|null Zwraca obiekt ogłoszenia lub null, jeśli nie znaleziono
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
     * Usuwa ogłoszenie.
     *
     * @param Notice $notice Encja ogłoszenia
     */
    public function delete(Notice $notice): void
    {
        $this->entityManager->remove($notice);
        $this->entityManager->flush();
    }

    /**
     * Can Notice be deleted?
     *
     * Sprawdza, czy ogłoszenie może być usunięte.
     *
     * @param Notice $notice Notice entity
     *
     * @return bool Czy ogłoszenie może być usunięte
     */
    public function canBeDeleted(Notice $notice): bool
    {
        // Tutaj możesz dodać dodatkowe logiki, np. czy są zależności, które blokują usunięcie.
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
