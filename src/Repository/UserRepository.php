<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * Class UserRepository.
 *
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Upgrade the user's password automatically over time.
     *
     * @param PasswordAuthenticatedUserInterface $user              User entity
     * @param string                             $newHashedPassword The new hashed password
     *
     * @throws UnsupportedUserException If the user is not an instance of the User class
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Find all users with partial data (optimized query).
     *
     * This method returns users with only the required fields to minimize data overhead.
     *
     * @return User[] Returns an array of User objects with limited fields
     */
    public function findAllWithPartialData(): array
    {
        return $this->getOrCreateQueryBuilder()
            ->select('partial u.{id, email, roles}')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find users by a specific field.
     *
     * @param mixed $value The value to search for
     *
     * @return User[] Returns an array of User objects
     */
    public function findByExampleField($value): array
    {
        return $this->getOrCreateQueryBuilder()
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find a single user by a specific field.
     *
     * @param mixed $value The value to search for
     *
     * @return User|null Returns a User object or null if no user is found
     */
    public function findOneBySomeField($value): ?User
    {
        return $this->getOrCreateQueryBuilder()
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find all users by role (optimized with partial data).
     *
     * @param string $role The role to filter users by
     *
     * @return User[] Returns an array of User objects with limited fields
     */
    public function findAllByRole(string $role): array
    {
        return $this->getOrCreateQueryBuilder()
            ->select('partial u.{id, email, roles}')
            ->andWhere('JSON_CONTAINS(u.roles, :role) = 1')  // SQL JSON_CONTAINS to filter users by role
            ->setParameter('role', json_encode($role))
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Optional query builder to modify
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(?QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('u');
    }
}
