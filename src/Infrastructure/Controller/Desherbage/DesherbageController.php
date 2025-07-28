<?php

namespace Infrastructure\Controller\Desherbage;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Infrastructure\Symfony\Service\SeoService;


class DesherbageController extends AbstractController
{
    public function __construct(
        private readonly SeoService $seoService
    ) {}

    #[Route('/desherbage', name: 'app_desherbage')]
    public function index()
    {
        $seoMeta = $this->seoService->getMetaForPage('desherbage');
        $structuredData = $this->seoService->getOrganizationStructuredData();

        return $this->render('client/desherbage/index.html.twig', [
            'seo_meta' => $seoMeta,
            'structured_data' => $structuredData,
        ]);
    }
}