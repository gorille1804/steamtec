<?php

namespace Infrastructure\Controller\home;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index()
    {
        return $this->render('client/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

}