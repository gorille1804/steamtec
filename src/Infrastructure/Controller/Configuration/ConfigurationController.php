<?php

namespace Infrastructure\Controller\Configuration;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Infrastructure\Symfony\Service\SeoService;

class ConfigurationController extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly SeoService $seoService
    ) {}

    #[Route('/configuration', name: 'app_configuration', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $seoMeta = $this->seoService->getMetaForPage('configuration');
        $structuredData = $this->seoService->getOrganizationStructuredData();

        return $this->render('client/configuration/index.html.twig', [
            'seo_meta' => $seoMeta,
            'structured_data' => $structuredData,
        ]);
    }
}
