<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixtures.
 */
class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Load data fixtures with the passed EntityManager.
     */
    public function load(ObjectManager $manager): void
    {
        // Tworzenie u≈ºytkownik√≥w
        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $user->setEmail(sprintf('user%d@example.com', $i));


            $user->setRoles([UserRole::ROLE_USER]);


            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'user1234')
            );


            $manager->persist($user);
        }
        // Tworzenie administratorÛw
        for ($i = 0; $i < 3; ++$i) {
            $admin = new User();

            // Ustawienie e-maila dla administratora
            $admin->setEmail(sprintf('admin%d@example.com', $i));

            // Ustawienie roli admina bez ->value, bo uøywamy zwyk≥ych sta≥ych
            $admin->setRoles([UserRole::ROLE_ADMIN]);

            // Haszowanie has≥a dla administratora
            $admin->setPassword(
                $this->passwordHasher->hashPassword($admin, 'admin1234')
            );

            // Zapisz administratora w menedøerze
            $manager->persist($admin);
        }

        // Zatwierdzenie operacji zapisu do bazy danych
        $manager->flush();
    }
}
