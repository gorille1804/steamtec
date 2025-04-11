<?php

namespace Infrastructure\Controller\FaqController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class FaqControllerPage extends AbstractController
{
    #[Route('/faq', name: 'app_faq')]
    public function index()
    {
        return $this->render('client/faq/index.html.twig');
    }
}