<?php

namespace Infrastructure\Controller\Security;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractController
{


    
    #[Route('/login', name: 'app_security')]
    public function index(){
        return $this->render('security/login.html.twig');
    }

    

}