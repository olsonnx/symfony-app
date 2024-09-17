<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Service;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Service handling security-related tasks.
 */
class SecurityService implements SecurityServiceInterface
{
    /**
     * Get login error and last username for login form.
     *
     * @param AuthenticationUtils $authenticationUtils Handles authentication related data
     *
     * @return array Contains 'last_username' and 'error'
     */
    public function getLoginData(AuthenticationUtils $authenticationUtils): array
    {
        return [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ];
    }
}
