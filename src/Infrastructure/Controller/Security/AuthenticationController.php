<?php

namespace Infrastructure\Controller\Security;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractController
{


    
    #[Route('/login', name: 'app_security')]
    public function index(){
        dd("tonga ato");
    }

}