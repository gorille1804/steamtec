<?php

namespace Infrastructure\Controller\HomeController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Infrastructure\Symfony\Service\SeoService;
use Domain\Faq\UseCase\GetAllFaqsUseCaseInterface;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly SeoService $seoService,
        private readonly GetAllFaqsUseCaseInterface $getAllFaqsUseCase
    ) {}

    #[Route('/', name: 'home')]
    public function index()
    {
        $seoMeta = $this->seoService->getMetaForPage('home');
        $structuredData = $this->seoService->getOrganizationStructuredData();
        $faqs = $this->getAllFaqsUseCase->__invoke();

        return $this->render('client/home/index.html.twig', [
            'controller_name' => 'HomeController',
            'seo_meta' => $seoMeta,
            'structured_data' => $structuredData,
            'faqs' => $faqs,
        ]);
    }
}