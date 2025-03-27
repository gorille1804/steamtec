<?php

namespace Infrastructure\Controller\MachineController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class MachineController extends AbstractController
{
    #[Route('/machine', name: 'app_machine')]
    public function index()
    {
        return $this->render('client/machine/index.html.twig');
    }
}