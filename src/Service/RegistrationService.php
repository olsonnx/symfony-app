<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Service for handling user registration.
 */
class RegistrationService implements RegistrationServiceInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;

    /**
     * Constructor.
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    /**
     * Register a new user.
     */
    public function register(User $user, string $plainPassword): void
    {
        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        // Persist the user to the database
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
