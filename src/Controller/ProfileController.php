<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserProfileType;
use App\Service\ProfileServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ProfileController.
 */
#[Route('/profile')]
class ProfileController extends AbstractController
{
    private ProfileServiceInterface $profileService;

    /**
     * Constructor.
     *
     * @param ProfileServiceInterface $profileService Service to manage user profiles
     */
    public function __construct(ProfileServiceInterface $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Show profile action.
     *
     * @return Response HTTP response displaying the user's profile
     */
    #[Route('/', name: 'profile', methods: ['GET'])]
    public function show(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException('You must be logged in to access this page.');
        }

        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Edit profile action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response with the profile edit form or redirection after successful update
     */
    #[Route('/edit', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException('You must be logged in to access this page.');
        }

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->profileService->updateProfile($user);
            $this->addFlash('success', 'Profile updated successfully.');

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
