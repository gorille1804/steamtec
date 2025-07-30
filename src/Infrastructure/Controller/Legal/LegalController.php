<?php

namespace Infrastructure\Controller\Legal;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Infrastructure\Symfony\Service\SeoService;

class LegalController extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly SeoService $seoService
    ) {}

    #[Route('/mentions-legales', name: 'app_legal', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $seoMeta = $this->seoService->getMetaForPage('legal');
        $structuredData = $this->seoService->getOrganizationStructuredData();

        return $this->render('client/legal/index.html.twig', [
            'seo_meta' => $seoMeta,
            'structured_data' => $structuredData,
        ]);
    }
} 