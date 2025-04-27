<?php

namespace Infrastructure\Controller\DecisionTree;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Domain\DecisionTree\UseCase\FindAllCategoriesUseCaseInterface;
use Domain\DecisionTree\Gateway\ProblemTypeRepositoryInterface;

#[Route('/dashboard')]
class DecisionTreeController extends AbstractController
{
    public function __construct(
        private readonly FindAllCategoriesUseCaseInterface $findAllCategories,
        private readonly ProblemTypeRepositoryInterface $problemTypeRepository
    ) {}

    #[Route('/arbre-de-depannage', name: 'app_arbre_de_depannage')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $categories = ($this->findAllCategories)();
        
        // Organiser les problèmes types par catégorie
        $problemTypesByCategory = [];
        foreach ($categories as $category) {
            $problemTypesByCategory[$category->id->getValue()] = $this->problemTypeRepository->findAllByCategory($category->id);
        }

        return $this->render('admin/decisiontree/index.html.twig', [
            'categories' => $categories,
            'problemTypesByCategory' => $problemTypesByCategory
        ]);
    }
} 