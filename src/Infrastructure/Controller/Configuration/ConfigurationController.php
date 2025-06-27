<?php

namespace Infrastructure\Controller\Configuration;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfigurationController extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {}

    #[Route('/configuration', name: 'app_configuration', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('client/configuration/index.html.twig', [
        ]);
    }
}
