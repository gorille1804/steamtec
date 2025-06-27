<?php

namespace Infrastructure\Controller\Accessoire;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class AccessoireController extends AbstractController
{
    #[Route('/accessoire', name: 'app_accessoire')]
    public function index()
    {
        return $this->render('client/accessoire/index.html.twig');
    }
}