<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service for handling profile-related operations.
 */
class ProfileService implements ProfileServiceInterface
{
    private EntityManagerInterface $entityManager;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager The entity manager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Update user profile.
     *
     * @param User $user The user entity
     */
    public function updateProfile(User $user): void
    {
        $this->entityManager->flush();
    }
}
