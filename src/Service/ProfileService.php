<?php

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
