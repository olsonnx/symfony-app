<?php

namespace App\Service;

use App\Entity\User;

/**
 * Interface for the Registration Service.
 */
interface RegistrationServiceInterface
{
    /**
     * Register a new user.
     */
    public function register(User $user, string $plainPassword): void;
}
