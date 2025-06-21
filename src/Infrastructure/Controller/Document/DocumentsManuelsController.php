<?php

namespace Infrastructure\Controller\Document;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Infrastructure\Symfony\Services\MultiplyRolesExpression;
use Domain\User\Data\Enum\RoleEnum;

#[Route('/dashboard')]
class DocumentsManuelsController extends AbstractController
{
    #[Route('/documents/manuels', name: 'app_documents_manuels', methods: ['GET'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function index(Request $request): Response
    {
        return $this->render('admin/documents/manuels/index.html.twig', [
            'title' => 'Manuels',
        ]);
    }
} 