<?php

namespace Infrastructure\Controller\Machine;

use Domain\Document\UseCase\DownloadDocumentUseCaseInterface;
use Domain\Document\UseCase\UploadDocumentUseCaseInterface;
use Domain\Machine\Data\Contract\CreateMachineRequest;
use Domain\Machine\UseCase\CreateMachineUseCaseInterface;
use Domain\Machine\UseCase\FindAllMachineUseCaseInterface;
use Domain\Machine\UseCase\FindMachineByIdUseCaseInterface;
use Infrastructure\Form\Machine\MachineFormType ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Domain\Machine\Factory\MachineFactory;
use Domain\Machine\Data\Contract\UpdateMachineRequest;
use Domain\Machine\Data\Model\Machine;
use Domain\User\Data\Model\User;
use Symfony\Contracts\Translation\TranslatorInterface;
use Domain\Machine\UseCase\DeleteMachineUseCaseInterface;
use Domain\Machine\UseCase\UpdateMachineUseCaseInterface;
use Domain\ParcMachine\UseCase\FindAllUsersByMachineUseCaseInterface;
use Domain\ParcMachine\UseCase\AddUserToMachineUseCaseInterface;
use Domain\ParcMachine\UseCase\RemoveUserFromMachineUseCaseInterface;
use Domain\User\UseCase\FindAllUsersUseCaseInterface;
use Domain\User\Data\ObjectValue\UserId;
use Infrastructure\Form\Machine\AddUserToMachineFormType;

#[Route('/dashboard')]
class MachineController extends AbstractController
{
    public function __construct(
        private readonly  CreateMachineUseCaseInterface $useCase,
        private readonly FindAllMachineUseCaseInterface $findAllUseCase,
        private readonly FindMachineByIdUseCaseInterface $findByIdUseCase,
        private readonly UpdateMachineUseCaseInterface $updateUseCase,
        private readonly DeleteMachineUseCaseInterface $deleteUseCase,
        private readonly UploadDocumentUseCaseInterface $uploadFileUseCase,
        private readonly UploadDocumentUseCaseInterface $uploadDocumentUseCase,
        private readonly DownloadDocumentUseCaseInterface $downloadDocumentUseCase,
        private readonly TranslatorInterface $translator,
        private readonly FindAllUsersByMachineUseCaseInterface $findAllUsersByMachineUseCase,
        private readonly AddUserToMachineUseCaseInterface $addUserToMachineUseCase,
        private readonly RemoveUserFromMachineUseCaseInterface $removeUserFromMachineUseCase,
        private readonly FindAllUsersUseCaseInterface $findAllUsersUseCase,
    ){}

    #[Route('/machines', name: 'app_machines')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $machines = $this->findAllUseCase->__invoke($page, $limit);
        $totalMachine = $this->findAllUseCase->getTotalMachines();
        $maxPages = ceil($totalMachine / $limit);

        return $this->render('admin/machine/index.html.twig', [
            'machines' => $machines,
            'currentPage' => $page,
            'maxPages' => $maxPages,
            'limit' => $limit
        ]);
    }

    #[Route('/machine/create', name: 'app_create_machine')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request)
    {
        $form=$this->createForm(MachineFormType::class, new CreateMachineRequest(), [
            'data_class' => CreateMachineRequest::class,
            'is_edit' => false
        ]);
        $form->handleRequest($request);

        
        if($form->isSubmitted() && $form->isValid()){  
            try {
                /** @var CreateMachineRequest $data */
                $data = $form->getData();
                $document = null;
                if($data->ficheTechnique){
                    $document = $this->uploadFileUseCase->__invoke($data->ficheTechnique);
                }
                $this->useCase->__invoke($data, $document);
                $this->addFlash('success', $this->translator->trans('machines.messages.create_succes'));
                return $this->redirectToRoute('app_machines');
            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('machines.messages.create_error'));
            }
        }

        return $this->render('admin/machine/create.html.twig',[
            'form' => $form->createView(),
            'is_edit' => false,
        ]);
    }

    #[Route('/machine/{machine}/edit', name:'app_update_machine', methods:['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(Request $request, Machine $machine): Response
    {
        $updateMachineRequest = MachineFactory::makeFromMachine($machine);
        $form = $this->createForm(MachineFormType::class, $updateMachineRequest, [
            'is_edit' => true,
            'data_class' => UpdateMachineRequest::class,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var UpdateMachineRequest $data */
                $data = $form->getData();
                $document = null;
                if($data->ficheTechnique){
                    $document = $this->uploadDocumentUseCase->__invoke($data->ficheTechnique);
                }
                $this->updateUseCase->__invoke($machine->id, $updateMachineRequest, $document);
                $this->addFlash('success', $this->translator->trans('machines.messages.update_succes'));
                return $this->redirectToRoute('app_machines');
            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('machines.messages.update_error'));
            }
        }

        // Récupérer les utilisateurs affectés à cette machine
        $assignedUsers = $this->findAllUsersByMachineUseCase->__invoke($machine);
        
        // Récupérer tous les utilisateurs disponibles
        $allUsers = $this->findAllUsersUseCase->__invoke();
        
        // Filtrer les utilisateurs non affectés
        $assignedUserIds = array_map(function($user) {
            return $user->getId()->getValue();
        }, $assignedUsers);
        
        $availableUsers = array_filter($allUsers, function($user) use ($assignedUserIds) {
            return !in_array($user->getId()->getValue(), $assignedUserIds);
        });

        // Créer le formulaire d'ajout d'utilisateur
        $addUserForm = $this->createForm(AddUserToMachineFormType::class, null, [
            'available_users' => $availableUsers,
        ]);

        $addUserForm->handleRequest($request);

        if ($addUserForm->isSubmitted() && $addUserForm->isValid()) {
            try {
                $data = $addUserForm->getData();
                $this->addUserToMachineUseCase->__invoke($machine, $data['user']);
                $this->addFlash('success', $this->translator->trans('machines.messages.add_user_success'));
                return $this->redirectToRoute('app_update_machine', ['machine' => $machine->id->getValue()]);
            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('machines.messages.add_user_error'));
            }
        }

        return $this->render('admin/machine/create.html.twig', [
            'form' => $form->createView(),
            'addUserForm' => $addUserForm->createView(),
            'is_edit' => true,
            'machine' => $machine,
            'assignedUsers' => $assignedUsers,
        ]);
    }

    #[Route('/machine/{machine}/delete', name:'app_delete_machine', methods:['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Machine $machine): Response
    {
        try {
            $this->deleteUseCase->__invoke($machine->id);
            $this->addFlash('success', $this->translator->trans('machines.messages.delete_succes'));
        } catch (\Exception $e) {
            $this->addFlash('error', $this->translator->trans('machines.messages.delete_error'));
        }

        return $this->redirectToRoute('app_machines');
    }

    #[Route('/machine/{machine}/remove-user/{user}', name:'app_remove_user_from_machine', methods:['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function removeUser(Machine $machine, User $user): Response
    {
        try {
            $this->removeUserFromMachineUseCase->__invoke($machine, $user);
            $this->addFlash('success', $this->translator->trans('machines.messages.remove_user_success'));
        } catch (\Exception $e) {
            $this->addFlash('error', $this->translator->trans('machines.messages.remove_user_error'));
        }

        return $this->redirectToRoute('app_update_machine', ['machine' => $machine->id->getValue()]);
    }

    #[Route('/machine/{machine}/download', name: 'app_download_machine_fiche_technique', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function download(Machine $machine)
    {
        if(!$machine->ficheTechnique){
            $this->addFlash('error', $this->translator->trans('machines.messages.error_download'));
            return $this->redirectToRoute('app_machines');
        }
        $this->downloadDocumentUseCase->__invoke($machine->ficheTechnique);
        return $this->redirectToRoute('app_machines');
    }
}