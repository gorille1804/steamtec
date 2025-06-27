<?php

namespace Infrastructure\Controller\FaqController;

use Domain\Faq\UseCase\GetAllFaqsUseCaseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class FaqControllerPage extends AbstractController
{
    public function __construct(
        private readonly GetAllFaqsUseCaseInterface $getAllFaqsUseCase,
    ) {}

    #[Route('/faq', name: 'app_faq')]
    public function index()
    {
        $faqs = $this->getAllFaqsUseCase->__invoke();
     
        return $this->render('client/faq/index.html.twig',[
            'faqs' => $faqs,
        ]);
    }
}