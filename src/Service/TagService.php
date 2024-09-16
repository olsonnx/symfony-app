<?php

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
     */
    public function __construct(TagRepository $tagRepository, EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $this->tagRepository = $tagRepository;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    /**
     * Find or create a tag by title.
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
     */
    public function findAll(): array
    {
        return $this->tagRepository->findAll();
    }

    /**
     * Find or create a tag by title.
     */
    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneBy(['title' => $title]);
    }

    /**
     * Save a tag entity.
     */
    public function save(Tag $tag): void
    {
        $this->entityManager->persist($tag);
        $this->entityManager->flush();
    }

    /**
     * Delete a tag entity.
     */
    public function delete(Tag $tag): void
    {
        $this->entityManager->remove($tag);
        $this->entityManager->flush();
    }

    /**
     * Get paginated list of tags with sorting.
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
     * @return Tag|null Tag entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Tag
    {
        return $this->tagRepository->findOneById($id);
    }
}
