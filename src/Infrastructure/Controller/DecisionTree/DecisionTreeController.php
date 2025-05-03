<?php

namespace Infrastructure\Controller\DecisionTree;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Domain\DecisionTree\UseCase\FindAllCategoriesUseCaseInterface;
use Domain\DecisionTree\Gateway\ProblemTypeRepositoryInterface;
use Domain\DecisionTree\Gateway\DiagnosticStepRepositoryInterface;
use Domain\DecisionTree\Service\DecisionTreeBuilder;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;

#[Route('/dashboard')]
class DecisionTreeController extends AbstractController
{
    public function __construct(
        private readonly FindAllCategoriesUseCaseInterface $findAllCategories,
        private readonly ProblemTypeRepositoryInterface $problemTypeRepository,
        private readonly DiagnosticStepRepositoryInterface $diagnosticStepRepository,
        private readonly DecisionTreeBuilder $decisionTreeBuilder
    ) {}

    #[Route('/arbre-de-depannage', name: 'app_arbre_de_depannage')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            throw new AccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }
        
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

    #[Route('/arbre-de-depannage/{problemTypeId}', name: 'app_arbre_de_depannage_show')]
    #[IsGranted('ROLE_USER')]
    public function show(string $problemTypeId): Response
    {
        if (!$this->getUser()) {
            throw new AccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $problemType = $this->problemTypeRepository->findById(new ProblemTypeId($problemTypeId));
        
        if (!$problemType) {
            throw $this->createNotFoundException('Ce type de problème n\'existe pas.');
        }

        $diagnosticSteps = $this->diagnosticStepRepository->findAllByProblemType($problemType->id);

        // Préparer les données pour l'arbre D3.js
        $nodes = [];
        $links = [];

        // Créer les nœuds
        foreach ($diagnosticSteps as $step) {
            $nodes[] = [
                'id' => $step->id->getValue(),
                'label' => $step->description,
                'type' => $step->stepType->value,
                'x' => 0, // Ces valeurs seront calculées par D3.js
                'y' => 0
            ];
        }

        // Créer les liens
        foreach ($diagnosticSteps as $step) {
            if ($step->nextStepOKId) {
                $links[] = [
                    'source' => $step->id->getValue(),
                    'target' => $step->nextStepOKId->getValue(),
                    'label' => 'OK'
                ];
            }
            if ($step->nextStepKOId) {
                $links[] = [
                    'source' => $step->id->getValue(),
                    'target' => $step->nextStepKOId->getValue(),
                    'label' => 'KO'
                ];
            }
        }

        $treeData = [
            'nodes' => $nodes,
            'links' => $links
        ];

        return $this->render('admin/decisiontree/show.html.twig', [
            'problemType' => $problemType,
            'diagnosticSteps' => $diagnosticSteps,
            'treeData' => $treeData
        ]);
    }
} 