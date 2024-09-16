<?php
namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserService.
 *
 * This service handles operations related to User entities.
 */
class UserService
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    /**
     * Constructor.
     *
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Get all users.
     *
     * @return User[] Returns an array of User objects
     */
    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * Save a user entity.
     *
     * @param User $user
     */
    public function saveUser(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Delete a user entity.
     *
     * @param User $user
     */
    public function deleteUser(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}