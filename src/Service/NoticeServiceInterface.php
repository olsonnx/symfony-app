<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Service;

use App\Dto\NoticeListInputFiltersDto;
use App\Entity\Notice;
use App\Entity\Tag;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface NoticeServiceInterface.
 */
interface NoticeServiceInterface
{
    /**
     * Get paginated list of notices.
     *
     * @param int                       $page    Page number
     * @param User|null                 $author  Notices author (optional)
     * @param NoticeListInputFiltersDto $filters Filters
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(int $page, ?User $author, NoticeListInputFiltersDto $filters): PaginationInterface;

    /**
     * Get a single notice by ID.
     *
     * @param int $noticeId ID ogłoszenia
     *
     * @return Notice|null Zwraca obiekt ogłoszenia lub null, jeśli nie znaleziono
     */
    public function getNotice(int $noticeId): ?Notice;

    /**
     * Save a notice.
     *
     * @param Notice $notice Encja ogłoszenia
     */
    public function save(Notice $notice): void;

    /**
     * Delete a notice.
     *
     * @param Notice $notice Encja ogłoszenia
     */
    public function delete(Notice $notice): void;

    /**
     * Can Notice be deleted?
     *
     * @param Notice $notice Notice entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Notice $notice): bool;

    /**
     * Find by title.
     *
     * @param string $title Tag title
     *
     * @return Tag|null Tag entity
     */
    public function findOneByTitle(string $title): ?Tag;
}
