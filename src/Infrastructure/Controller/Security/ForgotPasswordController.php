<?php

namespace Infrastructure\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordController extends AbstractController
{

    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function index()
    {
        return $this->render('security/forgot_password.html.twig');
    }

}