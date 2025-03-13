<?php

namespace Infrastructure\Controller\Shared;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class CommonAbtractController extends AbstractController
{
    protected function redirectAuthticatedUser()
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_dashboard');
        }
    }
}