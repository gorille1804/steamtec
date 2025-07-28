<?php

namespace Infrastructure\Controller\Nettoyage;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Infrastructure\Symfony\Service\SeoService;


class NettoyageController extends AbstractController
{
    public function __construct(
        private readonly SeoService $seoService
    ) {}

    #[Route('/nettoyage', name: 'app_nettoyage')]
    public function index()
    {
        $seoMeta = $this->seoService->getMetaForPage('nettoyage');
        $structuredData = $this->seoService->getOrganizationStructuredData();

        return $this->render('client/nettoyage/index.html.twig', [
            'seo_meta' => $seoMeta,
            'structured_data' => $structuredData,
        ]);
    }
}