<?php
/**
 * Notice management app
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Service;

use App\Entity\User;

/**
 * Interface for the Registration Service.
 */
interface RegistrationServiceInterface
{
    /**
     * Register a new user.
     *
     * @param User   $user          The user entity
     * @param string $plainPassword The plain text password
     */
    public function register(User $user, string $plainPassword): void;
}
