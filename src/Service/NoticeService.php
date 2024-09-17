<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
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
 *
 * Service responsible for handling notices.
 */
class NoticeService implements NoticeServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService  Category service
     * @param PaginatorInterface       $paginator        Paginator for pagination
     * @param TagServiceInterface      $tagService       Tag service
     * @param NoticeRepository         $noticeRepository Notice repository
     * @param EntityManagerInterface   $entityManager    Entity manager
     */
    public function __construct(private readonly CategoryServiceInterface $categoryService, private readonly PaginatorInterface $paginator, private readonly TagServiceInterface $tagService, private readonly NoticeRepository $noticeRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * Get paginated list of notices.
     *
     * @param int                       $page    Page number
     * @param User|null                 $author  Notice author
     * @param NoticeListInputFiltersDto $filters Filters for the notice listing
     *
     * @return PaginationInterface Paginated list of notices
     *
     * @throws NonUniqueResultException
     */
    public function getPaginatedList(int $page, ?User $author, NoticeListInputFiltersDto $filters): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        if ($author && in_array('ROLE_ADMIN', $author->getRoles())) {
            return $this->paginator->paginate(
                $this->noticeRepository->queryAll($filters),
                $page,
                self::PAGINATOR_ITEMS_PER_PAGE
            );
        }

        return $this->paginator->paginate(
            $this->noticeRepository->queryByAuthor($author, $filters),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get a single notice by ID.
     *
     * @param int $noticeId Notice ID
     *
     * @return Notice|null The notice or null if not found
     */
    public function getNotice(int $noticeId): ?Notice
    {
        return $this->noticeRepository->find($noticeId);
    }

    /**
     * Save a notice entity.
     *
     * @param Notice $notice The notice entity
     */
    public function save(Notice $notice): void
    {
        $this->entityManager->persist($notice);
        $this->entityManager->flush();
    }

    /**
     * Delete a notice entity.
     *
     * @param Notice $notice The notice entity
     */
    public function delete(Notice $notice): void
    {
        $this->entityManager->remove($notice);
        $this->entityManager->flush();
    }

    /**
     * Check if a notice can be deleted.
     *
     * @param Notice $notice The notice entity
     *
     * @return bool Whether the notice can be deleted
     */
    public function canBeDeleted(Notice $notice): bool
    {
        return true;
    }

    /**
     * Find a tag by title.
     *
     * @param string $title The tag title
     *
     * @return Tag|null The found tag or null if not found
     */
    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneByTitle($title);
    }

    /**
     * Prepare filters for the notice list.
     *
     * @param NoticeListInputFiltersDto $filters Raw filters from the request
     *
     * @return NoticeListFiltersDto Processed filters
     *
     * @throws NonUniqueResultException
     */
    private function prepareFilters(NoticeListInputFiltersDto $filters): NoticeListFiltersDto
    {
        return new NoticeListFiltersDto(
            null !== $filters->categoryId ? $this->categoryService->findOneById($filters->categoryId) : null,
            null !== $filters->tagId ? $this->tagService->findOneById($filters->tagId) : null,
            -1 === $filters->statusId ? null : NoticeStatus::from($filters->statusId)
        );
    }
}
