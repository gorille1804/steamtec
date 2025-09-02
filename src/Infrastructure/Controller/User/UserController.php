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
use Domain\User\UseCase\SearchUsersUseCaseInterface;
use Infrastructure\Form\User\UserFormType;
use Domain\ParcMachine\UseCase\FindAllMachinesByUserUseCaseInterface;
use Domain\ParcMachine\UseCase\AddMachineToUserUseCaseInterface;
use Domain\ParcMachine\UseCase\RemoveMachineFromUserUseCaseInterface;
use Domain\Machine\UseCase\GetAllMachinesUseCaseInterface;
use Infrastructure\Form\User\AddMachineToUserFormType;
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
        private readonly FindAllMachinesByUserUseCaseInterface $findAllMachinesByUserUseCase,
        private readonly AddMachineToUserUseCaseInterface $addMachineToUserUseCase,
        private readonly RemoveMachineFromUserUseCaseInterface $removeMachineFromUserUseCase,
        private readonly GetAllMachinesUseCaseInterface $getAllMachinesUseCase,
        private readonly SearchUsersUseCaseInterface $searchUsersUseCase,
    ){}

    #[Route('/users', name: 'app_users')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $search = $request->query->get('search', '');

        if (!empty($search)) {
            $users = $this->searchUsersUseCase->__invoke($search, $page, $limit);
            $totalUsers = $this->searchUsersUseCase->getTotalUsersWithSearch($search);
        } else {
            $users = $this->findAllUserUseCase->__invoke($page, $limit);
            $totalUsers = $this->findAllUserUseCase->getTotalUsers();
        }
        
        $maxPages = ceil($totalUsers / $limit);

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'currentPage' => $page,
            'maxPages' => $maxPages,
            'limit' => $limit,
            'search' => $search,
            'totalUsers' => $totalUsers
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
            $this->addFlash('error', $this->translator->trans('users.messages.reset_password_error') . ' ' . $e->getMessage());
        }
        return $this->redirectToRoute('app_users');
    }

    #[Route('/users/{user}/machines', name:'app_users_machines', methods:['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function manageMachines(Request $request, User $user): Response
    {
        // Récupérer les machines affectées à cet utilisateur
        $assignedMachines = $this->findAllMachinesByUserUseCase->__invoke($user);
        
        // Récupérer toutes les machines disponibles
        $allMachines = $this->getAllMachinesUseCase->__invoke();
        
        // Filtrer les machines non affectées
        $assignedMachineIds = array_map(function($machine) {
            return $machine->getId()->getValue();
        }, $assignedMachines);
        
        $availableMachines = array_filter($allMachines, function($machine) use ($assignedMachineIds) {
            return !in_array($machine->getId()->getValue(), $assignedMachineIds);
        });

        // Créer le formulaire d'ajout de machine
        $addMachineForm = $this->createForm(AddMachineToUserFormType::class, null, [
            'available_machines' => $availableMachines,
        ]);

        $addMachineForm->handleRequest($request);

        if ($addMachineForm->isSubmitted() && $addMachineForm->isValid()) {
            try {
                $data = $addMachineForm->getData();
                $this->addMachineToUserUseCase->__invoke($user, $data['machine']);
                $this->addFlash('success', $this->translator->trans('users.messages.add_machine_success'));
                return $this->redirectToRoute('app_users_machines', ['user' => $user->id->getValue()]);
            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('users.messages.add_machine_error'));
            }
        }

        return $this->render('admin/user/machines.html.twig', [
            'user' => $user,
            'assignedMachines' => $assignedMachines,
            'addMachineForm' => $addMachineForm->createView(),
        ]);
    }

    #[Route('/users/{user}/remove-machine/{machine}', name:'app_remove_machine_from_user', methods:['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function removeMachine(User $user, \Domain\Machine\Data\Model\Machine $machine): Response
    {
        try {
            $this->removeMachineFromUserUseCase->__invoke($user, $machine);
            $this->addFlash('success', $this->translator->trans('users.messages.remove_machine_success'));
        } catch (\Exception $e) {
            $this->addFlash('error', $this->translator->trans('users.messages.remove_machine_error'));
        }

        return $this->redirectToRoute('app_users_machines', ['user' => $user->id->getValue()]);
    }
}