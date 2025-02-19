<?php

namespace Infrastructure\Controller\UserMachine;

use Domain\Machine\UseCase\FindAllMachineByUserUseCaseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Domain\Machine\UseCase\FindAllMachineUseCaseInterface;
use Domain\User\Data\ObjectValue\UserId;
use Domain\User\UseCase\FindUserByIdUseCaseInterface;
use Symfony\Component\HttpFoundation\Request;
use Infrastructure\Form\Machine\ChoiceMachineFormType;
use Domain\Machine\Factory\MachineFactory;
use Domain\Machine\Data\Contract\ChoiceMachineRequest;
use Domain\Machine\Data\ObjectValue\MachineId;
use Domain\Machine\UseCase\UpdateMachineUseCaseInterface;
use Domain\Machine\UseCase\FindMachineByIdUseCaseInterface;

#[Route('/dashboard')]
class UserMachineController extends AbstractController
{
    public function __construct(
        private readonly FindAllMachineUseCaseInterface $findAllUseCase,
        private readonly FindAllMachineByUserUseCaseInterface $findAllByUserUseCase,
        private readonly FindUserByIdUseCaseInterface $findUserByIdUseCase,
        private readonly FindMachineByIdUseCaseInterface $findByIdUseCase,
        private readonly UpdateMachineUseCaseInterface $updateUseCase,
    ){}

    #[Route('/user_machines', name: 'app_user_machines')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ChoiceMachineFormType::class, new ChoiceMachineRequest(), [
            'data_class' => ChoiceMachineRequest::class
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            try {
                $data = $form->getData();
                $machineId = $data->machine->getId();
                $user=$this->getUser();
                $users = $this->findUserByIdUseCase->__invoke(new UserId($user->getId()));
                $data->machine->setUser($users);
                $machine=$data->machine;
                $updateMachineRequest = MachineFactory::makeFromMachine($machine);
                $this->updateUseCase->__invoke(new MachineId($machineId), $updateMachineRequest);
                $this->addFlash('success', 'Ajout machine dans le parc succès');
                return $this->redirectToRoute('app_user_machines');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la séléction de Machine');
            }
        }
        $user = $this->getUser();
        $users = $this->findUserByIdUseCase->__invoke(new UserId($user->getId()));
        $user_machines = $this->findAllByUserUseCase->__invoke($users);

        return $this->render('client/machine/index.html.twig', [
            'form' => $form->createView(),
            'user_machines' => $user_machines,
        ]);
    }


    #[Route('/user_machines/delete/{machineId}', name: 'app_delete_user_machine')]
    #[IsGranted('ROLE_USER')]
    public function deleteUserMachine(string $machineId): Response
    {
        try {
            $machine = $this->findByIdUseCase->__invoke(new MachineId($machineId));
            $machine->setUser(null);
            $updateMachineRequest = MachineFactory::makeFromMachine($machine);
            $this->updateUseCase->__invoke(new MachineId($machineId), $updateMachineRequest);
            $this->addFlash('success', 'Machine supprimé avec succès');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la suppression de la machine');
        }

        return $this->redirectToRoute('app_user_machines');
    }
}