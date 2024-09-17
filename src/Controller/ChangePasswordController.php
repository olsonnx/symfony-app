<?php
/**
 * Notice management app.
 *
 * Contact: aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Controller;

use App\Service\ChangePasswordServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ChangePasswordController.
 */
#[Route('/change-password')]
#[IsGranted('ROLE_USER')]
class ChangePasswordController extends AbstractController
{
    private ChangePasswordServiceInterface $changePasswordService;
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param ChangePasswordServiceInterface $changePasswordService Service for changing password
     * @param TranslatorInterface            $translator            Service for translations
     */
    public function __construct(ChangePasswordServiceInterface $changePasswordService, TranslatorInterface $translator)
    {
        $this->changePasswordService = $changePasswordService;
        $this->translator = $translator;
    }

    /**
     * Change password action.
     *
     * @param Request       $request HTTP request
     * @param UserInterface $user    The current authenticated user
     *
     * @return Response HTTP response with the form or redirection after success
     */
    #[Route('/', name: 'change_password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, UserInterface $user): Response
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('newPassword')->getData();
            $this->changePasswordService->changePassword($user, $newPassword);

            $this->addFlash('success', $this->translator->trans('message.password_changed_successfully'));

            return $this->redirectToRoute('profile');
        }

        return $this->render('security/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
