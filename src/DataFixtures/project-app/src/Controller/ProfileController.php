<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
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
    /**
     * Show profile action.
     */
    #[Route('/', name: 'profile', methods: ['GET'])]
    public function show(): Response
    {
        // Get the current authenticated user
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
     */
    #[Route('/edit', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException('You must be logged in to access this page.');
        }

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Profile updated successfully.');

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
