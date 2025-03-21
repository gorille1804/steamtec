<?php

namespace Infrastructure\Controller\Nettoyage;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class NettoyageController extends AbstractController
{
    #[Route('/nettoyage', name: 'app_nettoyage')]
    public function index()
    {
        return $this->render('client/nettoyage/index.html.twig');
    }
}