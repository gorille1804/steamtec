<?php

namespace Infrastructure\Controller\Desherbage;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class DesherbageController extends AbstractController
{
    #[Route('/desherbage', name: 'app_desherbage')]
    public function index()
    {
        return $this->render('client/desherbage/index.html.twig');
    }
}