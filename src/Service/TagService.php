<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Tag Service.
 */
class TagService implements TagServiceInterface
{
    private TagRepository $tagRepository;
    private EntityManagerInterface $entityManager;
    private PaginatorInterface $paginator;

    /**
     *  Constructor.
     *
     * @param TagRepository          $tagRepository Tag repository
     * @param EntityManagerInterface $entityManager Entity manager
     * @param PaginatorInterface     $paginator     Paginator
     */
    public function __construct(TagRepository $tagRepository, EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $this->tagRepository = $tagRepository;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    /**
     * Find or create a tag by title.
     *
     * @param string $title Tag title
     *
     * @return Tag Tag entity
     */
    public function findOrCreate(string $title): Tag
    {
        $tag = $this->tagRepository->findOneBy(['title' => $title]);

        if (!$tag) {
            $tag = new Tag();
            $tag->setTitle($title);
            $this->entityManager->persist($tag);
            $this->entityManager->flush();
        }

        return $tag;
    }

    /**
     * Get all tags.
     *
     * @return array List of all tags
     */
    public function findAll(): array
    {
        return $this->tagRepository->findAll();
    }

    /**
     * Find a tag by title.
     *
     * @param string $title Tag title
     *
     * @return Tag|null Tag entity or null
     */
    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneBy(['title' => $title]);
    }

    /**
     * Save a tag entity.
     *
     * @param Tag $tag Tag entity
     */
    public function save(Tag $tag): void
    {
        $this->entityManager->persist($tag);
        $this->entityManager->flush();
    }

    /**
     * Delete a tag entity.
     *
     * @param Tag $tag Tag entity
     */
    public function delete(Tag $tag): void
    {
        $this->entityManager->remove($tag);
        $this->entityManager->flush();
    }

    /**
     * Get paginated list of tags with sorting.
     *
     * @param int    $page      Page number
     * @param string $sort      Sort field
     * @param string $direction Sort direction
     *
     * @return PaginationInterface Paginated list of tags
     */
    public function getPaginatedList(int $page, string $sort, string $direction): PaginationInterface
    {
        $query = $this->tagRepository->createQueryBuilder('tag')
            ->orderBy($sort, $direction);

        return $this->paginator->paginate($query, $page, 10);
    }

    /**
     * Find by id.
     *
     * @param int $id Tag id
     *
     * @return Tag|null Tag entity or null
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Tag
    {
        return $this->tagRepository->findOneById($id);
    }
}
