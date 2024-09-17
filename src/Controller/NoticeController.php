<?php
/**
 * Notice management app.
 *
 * Contact: aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Controller;

use App\Dto\NoticeListInputFiltersDto;
use App\Entity\Notice;
use App\Entity\NoticeStatus;
use App\Form\Type\NoticeStatusType;
use App\Form\Type\NoticeType;
use App\Service\NoticeServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class NoticeController.
 */
#[Route('/notice')]
class NoticeController extends AbstractController
{
    private NoticeServiceInterface $noticeService;
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param NoticeServiceInterface $noticeService Service for handling notices
     * @param TranslatorInterface    $translator    Service for translations
     */
    public function __construct(NoticeServiceInterface $noticeService, TranslatorInterface $translator)
    {
        $this->noticeService = $noticeService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response with the list of notices
     */
    #[Route(name: 'notice_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $categoryId = $request->query->getInt('categoryId');
        $tagId = $request->query->getInt('tagId');
        $statusId = (int) ($this->isGranted('ROLE_ADMIN') ? NoticeStatus::STATUS_ACTIVE : $request->query->get('statusId', NoticeStatus::STATUS_ACTIVE));

        $filters = new NoticeListInputFiltersDto($categoryId, $tagId, $statusId);
        $pagination = $this->noticeService->getPaginatedList($page, null, $filters);

        return $this->render('notice/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Show action.
     *
     * @param Request $request HTTP request
     * @param Notice  $notice  Notice entity to display
     *
     * @return Response HTTP response displaying the notice details
     */
    #[Route('/{id}', name: 'notice_show', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('VIEW', subject: 'notice')]
    public function show(Request $request, Notice $notice): Response
    {
        $statusForm = $this->createForm(NoticeStatusType::class, ['status' => $notice->getStatus()]);
        $statusForm->handleRequest($request);

        if ($statusForm->isSubmitted() && $statusForm->isValid()) {
            $newStatus = $statusForm->get('status')->getData();
            $notice->setStatus($newStatus);
            $this->noticeService->save($notice);
            $this->addFlash('success', 'notification.success');

            return $this->redirectToRoute('notice_show', ['id' => $notice->getId()]);
        }

        $locale = $this->translator->getLocale();
        $dateFormatter = new \IntlDateFormatter(
            $locale,
            \IntlDateFormatter::LONG,
            \IntlDateFormatter::NONE
        );

        $formattedCreatedDate = $dateFormatter->format($notice->getCreatedAt());
        $formattedUpdatedDate = $dateFormatter->format($notice->getUpdatedAt());

        return $this->render('notice/show.html.twig', [
            'notice' => $notice,
            'statusForm' => $statusForm->createView(),
            'formattedCreatedDate' => $formattedCreatedDate,
            'formattedUpdatedDate' => $formattedUpdatedDate,
        ]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response for the notice creation form
     */
    #[Route('/create', name: 'notice_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $notice = new Notice();
        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->getUser()) {
                $notice->setAuthor($this->getUser());
                $notice->setStatus(NoticeStatus::STATUS_ACTIVE);
            } else {
                $notice->setStatus(NoticeStatus::STATUS_INACTIVE);
            }

            $this->noticeService->save($notice);
            $this->addFlash('success', $this->translator->trans('message.created_successfully'));

            return $this->redirectToRoute('notice_index');
        }

        return $this->render('notice/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Notice  $notice  Notice entity to edit
     *
     * @return Response HTTP response for the edit form
     */
    #[Route('/{id}/edit', name: 'notice_edit', requirements: ['id' => '\d+'], methods: ['GET', 'PUT'])]
    #[IsGranted('EDIT', subject: 'notice')]
    public function edit(Request $request, Notice $notice): Response
    {
        $form = $this->createForm(NoticeType::class, $notice, [
            'method' => 'PUT',
            'action' => $this->generateUrl('notice_edit', ['id' => $notice->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->noticeService->save($notice);
            $this->addFlash('success', $this->translator->trans('message.updated_successfully'));

            return $this->redirectToRoute('notice_index');
        }

        return $this->render('notice/edit.html.twig', [
            'form' => $form->createView(),
            'notice' => $notice,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Notice  $notice  Notice entity to delete
     *
     * @return Response HTTP response confirming the deletion
     */
    #[Route('/{id}/delete', name: 'notice_delete', requirements: ['id' => '\d+'], methods: ['GET', 'DELETE'])]
    #[IsGranted('DELETE', subject: 'notice')]
    public function delete(Request $request, Notice $notice): Response
    {
        if (!$this->noticeService->canBeDeleted($notice)) {
            $this->addFlash('warning', $this->translator->trans('message.notice_cannot_be_deleted'));

            return $this->redirectToRoute('notice_index');
        }

        $form = $this->createForm(FormType::class, $notice, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('notice_delete', ['id' => $notice->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->noticeService->delete($notice);
            $this->addFlash('success', $this->translator->trans('message.deleted_successfully'));

            return $this->redirectToRoute('notice_index');
        }

        return $this->render('notice/delete.html.twig', [
            'form' => $form->createView(),
            'notice' => $notice,
        ]);
    }
}
