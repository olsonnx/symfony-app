<?php

namespace App\Service;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Interface for security-related services.
 */
interface SecurityServiceInterface
{
    /**
     * Get login error and last username for login form.
     *
     * @return array Contains 'last_username' and 'error'
     */
    public function getLoginData(AuthenticationUtils $authenticationUtils): array;
}
