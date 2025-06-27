<?php

namespace Infrastructure\Controller\DecisionTree;

use Domain\DecisionTree\Data\Contract\CreateCategoryRequest;
use Domain\DecisionTree\Data\Contract\UpdateCategoryRequest;
use Domain\DecisionTree\Data\Model\Category;
use Infrastructure\Form\DecisionTree\CategoryFormType;
use Domain\DecisionTree\UseCase\FindAllCategoriesUseCaseInterface;
use Domain\DecisionTree\UseCase\CreateCategoryUseCaseInterface;
use Domain\DecisionTree\UseCase\FindCategoryByIdUseCaseInterface;
use Domain\DecisionTree\UseCase\UpdateCategoryUseCaseInterface;
use Domain\DecisionTree\UseCase\DeleteCategoryUseCaseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/dashboard/decision-tree')]
class CategoryController extends AbstractController
{
    public function __construct(
        private readonly FindAllCategoriesUseCaseInterface $findAllCategories,
        private readonly CreateCategoryUseCaseInterface $createCategory,
        private readonly FindCategoryByIdUseCaseInterface $findCategoryById,
        private readonly UpdateCategoryUseCaseInterface $updateCategory,
        private readonly DeleteCategoryUseCaseInterface $deleteCategory,
        private readonly TranslatorInterface $translator,
    ) {}

    #[Route('/categories', name: 'app_dt_category_index', methods: ['GET'])]
    public function index(): Response
    {
        $categories = ($this->findAllCategories)();
        return $this->render('admin/decision_tree/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categories/create', name: 'app_dt_category_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $dto = new CreateCategoryRequest();
        $form = $this->createForm(CategoryFormType::class, $dto, [
            'data_class' => CreateCategoryRequest::class,
            'is_edit' => false,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->createCategory->__invoke($dto);
            $this->addFlash('success', $this->translator->trans('Category created successfully.'));
            return $this->redirectToRoute('app_dt_category_index');
        }
        return $this->render('admin/decision_tree/category/create_update.html.twig', [
            'form' => $form->createView(),
            'is_edit' => false,
        ]);
    }

    #[Route('/categories/{category}/edit', name: 'app_dt_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category): Response
    {
        $dto = new UpdateCategoryRequest();
        $dto->name = $category->name;
        $form = $this->createForm(CategoryFormType::class, $dto, [
            'data_class' => UpdateCategoryRequest::class,
            'is_edit' => true,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->updateCategory->__invoke($category->id, $dto);
            $this->addFlash('success', $this->translator->trans('Category updated successfully.'));
            return $this->redirectToRoute('app_dt_category_index');
        }
        return $this->render('admin/decision_tree/category/create_update.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true,
            'category' => $category,
        ]);
    }

    #[Route('/categories/{category}/delete', name: 'app_dt_category_delete', methods: ['POST'])]
    public function delete(Category $category): Response
    {
        $this->deleteCategory->__invoke($category->id);
        $this->addFlash('success', $this->translator->trans('Category deleted successfully.'));
        return $this->redirectToRoute('app_dt_category_index');
    }
}
