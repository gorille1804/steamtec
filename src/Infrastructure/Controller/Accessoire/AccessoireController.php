<?php

namespace Infrastructure\Controller\Accessoire;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Infrastructure\Symfony\Service\SeoService;

class AccessoireController extends AbstractController
{
    public function __construct(
        private readonly SeoService $seoService
    ) {}

    #[Route('/accessoire', name: 'app_accessoire')]
    public function index()
    {
        $seoMeta = $this->seoService->getMetaForPage('accessoire');
        $structuredData = $this->seoService->getOrganizationStructuredData();

        return $this->render('client/accessoire/index.html.twig', [
            'seo_meta' => $seoMeta,
            'structured_data' => $structuredData,
        ]);
    }
}