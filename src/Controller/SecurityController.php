<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Controller;

use App\Service\SecurityServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController.
 */
class SecurityController extends AbstractController
{
    private SecurityServiceInterface $securityService;

    /**
     * Constructor.
     *
     * @param SecurityServiceInterface $securityService Service to handle security-related logic
     */
    public function __construct(SecurityServiceInterface $securityService)
    {
        $this->securityService = $securityService;
    }

    /**
     * Login action.
     *
     * @param AuthenticationUtils $authenticationUtils Utility for handling authentication data
     *
     * @return Response HTTP response with login form or error information
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $loginData = $this->securityService->getLoginData($authenticationUtils);

        return $this->render('security/login.html.twig', [
            'last_username' => $loginData['last_username'],
            'error' => $loginData['error'],
        ]);
    }

    /**
     * Logout action.
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
