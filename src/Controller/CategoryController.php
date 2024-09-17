<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Controller;

use App\Entity\Category;
use App\Form\Type\CategoryType;
use App\Service\CategoryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CategoryController.
 */
#[Route('/category')]
class CategoryController extends AbstractController
{
    private CategoryServiceInterface $categoryService;
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService Category service
     * @param TranslatorInterface      $translator      Translator
     */
    public function __construct(CategoryServiceInterface $categoryService, TranslatorInterface $translator)
    {
        $this->categoryService = $categoryService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response with paginated category list.
     */
    #[Route(name: 'category_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        // Pobierz numer strony z zapytania, domyślnie ustaw 1
        $page = $request->query->getInt('page', 1);

        // Pobierz paginowaną listę kategorii
        $pagination = $this->categoryService->getPaginatedList($page);

        return $this->render('category/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Show action.
     *
     * @param Category $category Category entity
     *
     * @return Response HTTP response with category details and formatted notices.
     */
    #[Route('/{id}', name: 'category_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[IsGranted('VIEW', subject: 'category')]
    public function show(Category $category): Response
    {
        $categoryDetails = $this->categoryService->getCategoryWithNotices($category->getId());

        // Tworzymy formatter daty zgodnie z bieżącym locale aplikacji
        $locale = $this->translator->getLocale();  // Pobieramy aktualne locale
        $formatter = new \IntlDateFormatter(
            $locale,
            \IntlDateFormatter::LONG,
            \IntlDateFormatter::NONE
        );

        // Formatowanie daty dla ogłoszeń w kategorii
        $formattedNotices = array_map(function ($notice) use ($formatter) {
            $createdAt = $notice->getCreatedAt();
            $updatedAt = $notice->getUpdatedAt();

            // Sprawdzamy, czy daty są ustawione, zanim spróbujemy je sformatować
            $notice->createdAtFormatted = $createdAt ? $formatter->format($createdAt) : null;
            $notice->updatedAtFormatted = $updatedAt ? $formatter->format($updatedAt) : null;

            return $notice;
        }, $categoryDetails['notices']);

        return $this->render('category/show.html.twig', [
            'category' => $categoryDetails['category'],
            'notices' => $formattedNotices,
        ]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response with category creation form.
     */
    #[Route('/create', name: 'category_create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);
            $this->addFlash('success', $this->translator->trans('message.created_successfully'));

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit action.
     *
     * @param Request  $request  HTTP request
     * @param Category $category Category entity
     *
     * @return Response HTTP response with category edit form.
     */
    #[Route('/{id}/edit', name: 'category_edit', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'PUT'])]
    #[IsGranted('EDIT', subject: 'category')]
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category, [
            'method' => 'PUT',
            'action' => $this->generateUrl('category_edit', ['id' => $category->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);
            $this->addFlash('success', $this->translator->trans('message.updated_successfully'));

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request  $request  HTTP request
     * @param Category $category Category entity
     *
     * @return Response HTTP response after deletion process.
     */
    #[Route('/{id}/delete', name: 'category_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('DELETE', subject: 'category')]
    public function delete(Request $request, Category $category): Response
    {
        if (!$this->categoryService->canBeDeleted($category)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.category_contains_tasks')
            );

            return $this->redirectToRoute('category_index');
        }

        $form = $this->createForm(
            FormType::class,
            $category,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('category_delete', ['id' => $category->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->delete($category);
            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/delete.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }
}
