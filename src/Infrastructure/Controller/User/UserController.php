<?php

namespace Infrastructure\Controller\User;

use Domain\User\UseCase\FindAllUserUseCaseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[Route('/dashboard')]
class UserController extends AbstractController
{

    public function __construct(
      private readonly  FindAllUserUseCaseInterface $findAllUserUseCase
    ){}

    #[Route('/users', name: 'app_users')]
    #[IsGranted('ROLE_ADMIN')]
    public function index()
    {
        $users = $this->findAllUserUseCase->__invoke();
        return $this->render('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }
}