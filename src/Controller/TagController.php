<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Form\Type\TagType;
use App\Service\TagServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class TagController.
 */
#[Route('/tag')]
class TagController extends AbstractController
{
    private TagServiceInterface $tagService;
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param TagServiceInterface $tagService Service to handle tag-related operations
     * @param TranslatorInterface $translator Translator for internationalization
     */
    public function __construct(TagServiceInterface $tagService, TranslatorInterface $translator)
    {
        $this->tagService = $tagService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response with paginated list of tags
     */
    #[Route(name: 'tag_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $sort = $request->query->get('sort', 'tag.title');
        $direction = $request->query->get('direction', 'asc');

        $pagination = $this->tagService->getPaginatedList($page, $sort, $direction);

        // Tworzenie formatera daty zgodnie z locale
        $locale = $this->translator->getLocale();
        $formatter = new \IntlDateFormatter(
            $locale,
            \IntlDateFormatter::LONG,
            \IntlDateFormatter::NONE
        );

        foreach ($pagination->getItems() as $tag) {
            $tag->createdAtFormatted = $tag->getCreatedAt() ? $formatter->format($tag->getCreatedAt()) : null;
            $tag->updatedAtFormatted = $tag->getUpdatedAt() ? $formatter->format($tag->getUpdatedAt()) : null;
        }

        return $this->render('tag/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response after creating a new tag
     */
    #[Route('/create', name: 'tag_create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tag);
            $this->addFlash('success', $this->translator->trans('message.created_successfully'));

            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Tag     $tag     Tag entity
     *
     * @return Response HTTP response after editing a tag
     */
    #[Route('/{id}/edit', name: 'tag_edit', methods: ['GET', 'PUT'])]
    #[IsGranted('EDIT', subject: 'tag')]
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag, [
            'method' => 'PUT',
            'action' => $this->generateUrl('tag_edit', ['id' => $tag->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tag);
            $this->addFlash('success', $this->translator->trans('message.updated_successfully'));

            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/edit.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Tag     $tag     Tag entity
     *
     * @return Response HTTP response after deleting a tag
     */
    #[Route('/{id}/delete', name: 'tag_delete', methods: ['GET', 'DELETE'])]
    #[IsGranted('DELETE', subject: 'tag')]
    public function delete(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(FormType::class, $tag, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('tag_delete', ['id' => $tag->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->delete($tag);
            $this->addFlash('success', $this->translator->trans('message.deleted_successfully'));

            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/delete.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag,
        ]);
    }
}
