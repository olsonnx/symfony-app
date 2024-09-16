<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Admin controller for managing users.
 *
 * This controller handles all user-related operations in the admin panel,
 * including listing, creating, editing, and deleting users.
 */
#[Route('/admin/user')]
class AdminUserController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * Initializes the EntityManager and Translator for use in all methods.
     *
     * @param EntityManagerInterface $entityManager Entity manager for database interactions
     * @param TranslatorInterface    $translator    Translator for handling translations
     */
    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * List all users.
     *
     * Fetches and displays all users from the database in the admin user listing page.
     *
     * @param UserRepository $userRepository Repository for querying user data
     *
     * @return Response HTTP response containing the user list view
     */
    #[Route('/', name: 'admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * Create a new user.
     *
     * Handles both displaying the form to create a new user and processing the form submission.
     * After successful form submission and validation, the user is saved in the database.
     *
     * @param Request $request The HTTP request containing form data
     *
     * @return Response HTTP response containing the user creation form or redirection after creation
     */
    #[Route('/new', name: 'admin_user_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the new user to the database
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // Add success message after user creation
            $this->addFlash('success', $this->translator->trans('message.user_created_successfully'));

            return $this->redirectToRoute('admin_user_index');
        }

        // Render the form to create a new user
        return $this->render('admin/user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit an existing user.
     *
     * Displays the form to edit an existing user and handles form submission. After successful submission
     * and validation, the user is updated in the database.
     *
     * @param Request $request The HTTP request containing form data
     * @param User    $user    The user entity to be edited
     *
     * @return Response HTTP response containing the user edit form or redirection after update
     */
    #[Route('/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update the existing user in the database
            $this->entityManager->flush();

            // Add success message after user update
            $this->addFlash('success', $this->translator->trans('message.user_updated_successfully'));

            return $this->redirectToRoute('admin_user_index');
        }

        // Render the form to edit the user
        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a user.
     *
     * Deletes the user from the database after confirming the CSRF token. If the deletion is successful,
     * a success message is shown.
     *
     * @param Request $request The HTTP request containing CSRF token
     * @param User    $user    The user entity to be deleted
     *
     * @return Response HTTP response redirecting to the user list after deletion
     */
    #[Route('/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        // Validate the CSRF token before deletion
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            // Remove the user from the database
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            // Add success message after user deletion
            $this->addFlash('success', $this->translator->trans('message.user_deleted_successfully'));
        }

        return $this->redirectToRoute('admin_user_index');
    }
}
