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
use Domain\DecisionTree\Data\ObjectValue\DiagnosticStepId;

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
        // On ne passe plus de données, tout sera chargé côté JS
        return $this->render('admin/decisiontree/index.html.twig');
    }

    // Optionnel : API pour servir le JSON (sinon, le front peut lire le fichier public/assets/data/decision-tree.json directement)
    #[Route('/arbre-de-depannage/data', name: 'app_arbre_de_depannage_data')]
    #[IsGranted('ROLE_USER')]
    public function data(): Response
    {
        $jsonPath = $this->getParameter('kernel.project_dir') . '/public/assets/data/decision-tree.json';
        $json = file_get_contents($jsonPath);
        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }
}
