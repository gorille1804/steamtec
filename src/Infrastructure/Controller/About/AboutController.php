<?php

namespace Infrastructure\Controller\About;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class AboutController extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {}

    #[Route('/about-us', name: 'app_about', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('client/apropos/index.html.twig', [
        ]);
    }
}
