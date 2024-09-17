<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Service;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface for ChangePasswordService.
 */
interface ChangePasswordServiceInterface
{
    /**
     * Change the password for a user.
     *
     * @param UserInterface $user        The user entity
     * @param string        $newPassword The new password
     */
    public function changePassword(UserInterface $user, string $newPassword): void;
}
