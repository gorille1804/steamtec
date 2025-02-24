<?php

namespace Infrastructure\Controller\HomeController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index()
    {
        return $this->render('client/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}