<?php

namespace Infrastructure\Controller\User;

use Domain\User\Data\Contract\CreateUserRequest;
use Domain\User\Data\Contract\UpdateUserRequest;
use Domain\User\Data\Model\User;
use Domain\User\Data\ObjectValue\UserId;
use Domain\User\Factory\UserFactory;
use Domain\User\UseCase\CreateUserUseCaseInterface;
use Domain\User\UseCase\DeleteUserUseCaseInterface;
use Domain\User\UseCase\UpdateUserUseCaseInterface;
use Domain\User\UseCase\FindAllUserUseCaseInterface;
use Domain\User\UseCase\FindUserByIdUseCaseInterface;
use Domain\User\UseCase\SendCreatePasswordEmailUseCaseInterface;
use Infrastructure\Form\User\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/dashboard')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly FindAllUserUseCaseInterface $findAllUserUseCase,
        private readonly FindUserByIdUseCaseInterface $findUserByIdUseCase,
        private readonly CreateUserUseCaseInterface $createUserUseCase,
        private readonly UpdateUserUseCaseInterface $updateUserUseCase,
        private readonly DeleteUserUseCaseInterface $deleteUseCase,
        private readonly SendCreatePasswordEmailUseCaseInterface $sendCreatePasswordEmailUseCase,
        private readonly TranslatorInterface $translator,
    ){}

    #[Route('/users', name: 'app_users')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $users = $this->findAllUserUseCase->__invoke($page, $limit);
        $totalUsers = $this->findAllUserUseCase->getTotalUsers();
        $maxPages = ceil($totalUsers / $limit);

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'currentPage' => $page,
            'maxPages' => $maxPages,
            'limit' => $limit
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
                $this->addFlash('success', $this->translator->trans('users.messages.create_succes'));
                return $this->redirectToRoute('app_users');
            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('users.messages.create_error'));
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
                $this->addFlash('success', $this->translator->trans('users.messages.update_succes'));
                return $this->redirectToRoute('app_users');
            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('users.messages.update_error'));
            }
        }

        return $this->render('admin/user/create_update.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true,
            'user' => $user,
        ]);
    }

    #[Route('/users/{user}/delete', name:'app_users_delete', methods:['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, User $user): Response
    {
        try {
            $this->deleteUseCase->__invoke($user->id);
            $this->addFlash('success', $this->translator->trans('users.messages.update_succes'));
        } catch (\Exception $e) {
            $this->addFlash('error',  $this->translator->trans('users.messages.update_error'));
        }

        return $this->redirectToRoute('app_users');
    }

    #[Route('/users/{user}/create-password', name:'app_users_reset_password', methods:['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function resetPassword(User $user): Response
    {
        try {
            $this->sendCreatePasswordEmailUseCase->__invoke($user, 'email/security/create_password.html.twig');
            $this->addFlash('success',  $this->translator->trans('users.messages.reset_password_succes'));
        } catch (\Exception $e) {
            $this->addFlash('error', $this->translator->trans('users.messages.reset_password_error'));
        }
        return $this->redirectToRoute('app_users');
    }
}