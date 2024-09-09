<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Enum\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixtures.
 */
class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Load data fixtures with the passed EntityManager
     */
    public function load(ObjectManager $manager): void
    {
        // Tworzenie użytkowników
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail(sprintf('user%d@example.com', $i));
            $user->setRoles([UserRole::ROLE_USER->value]);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'user1234')
            );

            $manager->persist($user);
        }

        // Tworzenie administratorów
        for ($i = 0; $i < 3; $i++) {
            $admin = new User();
            $admin->setEmail(sprintf('admin%d@example.com', $i));
            $admin->setRoles([UserRole::ROLE_ADMIN->value]);
            $admin->setPassword(
                $this->passwordHasher->hashPassword($admin, 'admin1234')
            );

            $manager->persist($admin);
        }

        $manager->flush();
    }
}