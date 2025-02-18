<?php

namespace Infrastructure\Controller\Dashboard;

use Infrastructure\Symfony\Services\MultiplyRolesExpression;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Domain\User\Data\Enum\RoleEnum;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_home', methods: ['GET'])]
    
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function index()
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}