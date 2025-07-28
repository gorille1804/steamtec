<?php

namespace Infrastructure\Controller\Utilisateur;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Infrastructure\Symfony\Service\SeoService;

class UtilisateurController extends AbstractController
{
    public function __construct(
        private readonly SeoService $seoService
    ) {}

    #[Route('/clients', name: 'app_utilisateur')]
    public function index(): Response
    {
        $seoMeta = $this->seoService->getMetaForPage('client');
        $structuredData = $this->seoService->getOrganizationStructuredData();

        return $this->render('client/utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
            'seo_meta' => $seoMeta,
            'structured_data' => $structuredData,
        ]);
    }
}
