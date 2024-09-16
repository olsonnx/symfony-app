<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Service Entity Repository.
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * Find a tag by its title (name).
     */
    public function findOneByTitle(string $title): ?Tag
    {
        return $this->findOneBy(['name' => $title]); // Zakładamy, że 'name' jest polem w encji Tag
    }
}
