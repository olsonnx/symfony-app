<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class CategoryRepository.
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Query all categories with their associated notices.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAllWithNotices(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select('category', 'partial notice.{id, title}')
            ->leftJoin('category.notices', 'notice')  // Left join to get associated notices
            ->orderBy('category.updatedAt', 'DESC');
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
        return $queryBuilder ?? $this->createQueryBuilder('category');
    }

    /**
     * Save entity.
     */
    public function save(Category $category): void
    {
        $this->_em->persist($category);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     */
    public function delete(Category $category): void
    {
        $this->_em->remove($category);
        $this->_em->flush();
    }
}
