<?php

namespace Infrastructure\Controller;

use Domain\User\UseCase\FindAllUserUseCaseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    public function __construct(
      private readonly  FindAllUserUseCaseInterface $findAllUserUseCase
    ){}

    #[Route('/user', name: 'app_user')]
    public function index()
    {
        $users = $this->findAllUserUseCase->__invoke();
        return $this->render('client/index.html.twig', [
            'users' => $users
        ]);
    }
}