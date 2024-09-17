<?php
/**
 * Notice management app
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Service;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service for handling password changes.
 */
class ChangePasswordService implements ChangePasswordServiceInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;

    /**
     * Constructor.
     *
     * @param UserPasswordHasherInterface $passwordHasher The password hasher service
     * @param EntityManagerInterface      $entityManager  The Doctrine entity manager
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    /**
     * Change the password for a user.
     *
     * @param UserInterface $user        The user entity
     * @param string        $newPassword The new password
     */
    public function changePassword(UserInterface $user, string $newPassword): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);
        $this->entityManager->flush();
    }
}
