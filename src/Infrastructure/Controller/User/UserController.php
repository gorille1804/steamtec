<?php

namespace Infrastructure\Controller\User;

use Domain\User\Data\Contract\CreateUserRequest;
use Domain\User\Data\Contract\UpdateUserRequest;
use Domain\User\Data\ObjectValue\UserId;
use Domain\User\Factory\UserFactory;
use Domain\User\UseCase\CreateUserUseCaseInterface;
use Domain\User\UseCase\DeleteUserUseCaseInterface;
use Domain\User\UseCase\UpdateUserUseCaseInterface;
use Domain\User\UseCase\FindAllUserUseCaseInterface;
use Domain\User\UseCase\FindUserByIdUseCaseInterface;
use Infrastructure\Form\User\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly FindAllUserUseCaseInterface $findAllUserUseCase,
        private readonly FindUserByIdUseCaseInterface $findUserByIdUseCase,
        private readonly CreateUserUseCaseInterface $createUserUseCase,
        private readonly UpdateUserUseCaseInterface $updateUserUseCase,
        private readonly DeleteUserUseCaseInterface $deleteUseCase
    ){}

    #[Route('/users', name: 'app_users')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        $users = $this->findAllUserUseCase->__invoke();
        return $this->render('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/users/create', name:'app_users_create', methods:['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $createUserRequest = new CreateUserRequest();
        
        $form = $this->createForm(UserFormType::class, $createUserRequest, [
            'is_edit' => false,
            'data_class' => CreateUserRequest::class,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->createUserUseCase->__invoke($createUserRequest);
                $this->addFlash('success', 'Utilisateur créé avec succès');
                return $this->redirectToRoute('app_users');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la création de l\'utilisateur');
            }
        }

        return $this->render('admin/user/create_update.html.twig', [
            'form' => $form->createView(),
            'is_edit' => false,
        ]);
    }

    #[Route('/users/{userId}/edit', name:'app_users_update', methods:['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(Request $request, string $userId): Response
    {
        $user = $this->findUserByIdUseCase->__invoke(new UserId($userId));
        $updateUserRequest = UserFactory::makeFromUser($user);
        $form = $this->createForm(UserFormType::class, $updateUserRequest, [
            'is_edit' => true,
            'data_class' => UpdateUserRequest::class,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->updateUserUseCase->__invoke(new UserId($userId), $updateUserRequest);
                $this->addFlash('success', 'Utilisateur mis à jour avec succès');
                return $this->redirectToRoute('app_users');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la mise à jour de l\'utilisateur');
            }
        }

        return $this->render('admin/user/create_update.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true,
            'user' => $user,
        ]);
    }

    #[Route('/users/{userId}/delete', name:'app_users_delete', methods:['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, string $userId): Response
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete' . $userId, $submittedToken)) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_users');
        }

        try {
            $this->deleteUseCase->__invoke(new UserId($userId));
            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la suppression de l\'utilisateur');
        }

        return $this->redirectToRoute('app_users');
    }
}