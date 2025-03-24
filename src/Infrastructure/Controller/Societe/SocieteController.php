<?php

namespace Infrastructure\Controller\Societe;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class SocieteController extends AbstractController
{
    #[Route('/societe', name: 'app_societe')]
    public function index()
    {
        return $this->render('client/societe/index.html.twig');
    }
}