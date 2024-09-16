<?php
/**
 * Notice repository.
 */

namespace App\Repository;

use App\Dto\NoticeListFiltersDto;
use App\Entity\Category;
use App\Entity\Notice;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class NoticeRepository.
 *
 * @method Notice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notice[]    findAll()
 * @method Notice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Notice>
 */
class NoticeRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notice::class);
    }

    /**
     * Query all records with filters.
     *
     * @param NoticeListFiltersDto $filters Filters for the query
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(NoticeListFiltersDto $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial notice.{id, createdAt, updatedAt, title, content}',
                'partial category.{id, title}',
                'partial tags.{id, title}'
            )
            ->join('notice.category', 'category')
            ->leftJoin('notice.tags', 'tags')
            ->orderBy('notice.updatedAt', 'DESC');

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    /**
     * Query notices by author with filters.
     *
     * @param User|null            $author  Author of the notices
     * @param NoticeListFiltersDto $filters Filters for the query
     */
    public function queryByAuthor(?User $author, NoticeListFiltersDto $filters): QueryBuilder
    {
        $queryBuilder = $this->queryAll($filters);

        if ($author) {
            $queryBuilder->andWhere('notice.author = :author')
                ->setParameter('author', $author);
        }

        return $queryBuilder;
    }

    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder         $queryBuilder Query builder
     * @param NoticeListFiltersDto $filters      Filters
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, NoticeListFiltersDto $filters): QueryBuilder
    {
        if ($filters->getCategory()) {
            $queryBuilder->andWhere('notice.category = :category')
                ->setParameter('category', $filters->getCategory());
        }

        if ($filters->getTag()) {
            $queryBuilder->join('notice.tags', 'tag')
                ->andWhere('tag = :tag')
                ->setParameter('tag', $filters->getTag());
        }

        // Dodaj filtr statusu tylko wtedy, gdy nie jest null
        if (null !== $filters->getStatus()) {
            $queryBuilder->andWhere('notice.status = :status')
                ->setParameter('status', $filters->getStatus());
        }

        return $queryBuilder;
    }

    /**
     * Count notices by category.
     *
     * @param Category $category Category
     *
     * @return int Number of notices in category
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByCategory(Category $category): int
    {
        return $this->getOrCreateQueryBuilder()
            ->select('COUNT(notice.id)')
            ->where('notice.category = :category')
            ->setParameter(':category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Save entity.
     *
     * @param Notice $notice Notice entity
     */
    public function save(Notice $notice): void
    {
        $this->_em->persist($notice);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Notice $notice Notice entity
     */
    public function delete(Notice $notice): void
    {
        $this->_em->remove($notice);
        $this->_em->flush();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(?QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('notice');
    }
}
