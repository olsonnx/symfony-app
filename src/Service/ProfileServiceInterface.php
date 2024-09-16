<?php

namespace App\Service;

use App\Entity\User;

/**
 * Interface for ProfileService.
 */
interface ProfileServiceInterface
{
    /**
     * Update user profile.
     *
     * @param User $user The user entity
     */
    public function updateProfile(User $user): void;
}
