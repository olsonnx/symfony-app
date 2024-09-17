<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixtures.
 *
 * This class is responsible for loading user and admin data into the database.
 */
class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Constructor.
     *
     * @param UserPasswordHasherInterface $passwordHasher The password hasher service
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager The object manager for persisting data
     */
    public function load(ObjectManager $manager): void
    {
        // Tworzenie użytkowników
        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $user->setEmail(sprintf('user%d@example.com', $i));
            $user->setRoles([UserRole::ROLE_USER]);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'user1234')
            );

            $manager->persist($user);
        }

        // Tworzenie administratorów
        for ($i = 0; $i < 3; ++$i) {
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
