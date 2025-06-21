<?php

namespace Infrastructure\Controller\Entretien;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Infrastructure\Symfony\Services\MultiplyRolesExpression;
use Domain\User\Data\Enum\RoleEnum;

#[Route('/dashboard')]
class EntretienController extends AbstractController
{
    #[Route('/entretiens/quotidien', name: 'app_entretiens_quotidien', methods: ['GET'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function quotidien(Request $request): Response
    {
        return $this->render('admin/entretien/quotidien/index.html.twig', [
            'title' => 'Activités quotidiennes et hebdomadaires',
        ]);
    }

    #[Route('/entretiens/regulier', name: 'app_entretiens_regulier', methods: ['GET'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function regulier(Request $request): Response
    {
        return $this->render('admin/entretien/regulier/index.html.twig', [
            'title' => 'Entretien régulier machine',
        ]);
    }

    #[Route('/entretiens/ponctuel', name: 'app_entretiens_ponctuel', methods: ['GET'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function ponctuel(Request $request): Response
    {
        return $this->render('admin/entretien/ponctuel/index.html.twig', [
            'title' => 'Entretien ponctuel machine',
        ]);
    }

    #[Route('/entretiens/aides', name: 'app_entretiens_aides', methods: ['GET'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function aides(Request $request): Response
    {
        return $this->render('admin/entretien/aides/index.html.twig', [
            'title' => 'Liste des aides à l\'entretien',
        ]);
    }
} 