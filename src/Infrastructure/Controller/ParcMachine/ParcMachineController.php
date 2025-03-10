<?php

namespace Infrastructure\Controller\ParcMachine;

use Domain\ParcMachine\Data\ObjectValue\ParcMachineId;
use Domain\ParcMachine\Factory\ParcMachineFactory;
use Domain\ParcMachine\UseCase\CreateParcMachineUseCaseInterface;
use Domain\ParcMachine\UseCase\FindAllParcMachineByUserUseCaseInterface;
use Infrastructure\Form\ParcMachine\ParcMachineFormType ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Domain\User\UseCase\FindUserByIdUseCaseInterface;
use Domain\ParcMachine\UseCase\FindParcMachineByIdUseCaseInterface;
use Domain\ParcMachine\UseCase\DeleteParcMachineUseCaseInterface;
use Infrastructure\Symfony\Security\SymfonyUserAdapter;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/dashboard')]
class ParcMachineController extends AbstractController
{
    public function __construct(
        private readonly  CreateParcMachineUseCaseInterface $createUseCase,
        private readonly FindUserByIdUseCaseInterface $findUserByIdUseCase,
        private readonly FindAllParcMachineByUserUseCaseInterface $findAllByUserUseCase,
        private readonly FindParcMachineByIdUseCaseInterface $findParcMachineByIdUseCase,
        private readonly DeleteParcMachineUseCaseInterface $deleteParcMachineUseCase,
        private readonly TranslatorInterface $translator,
    ){}

    #[Route('/parcMachines', name: 'app_parc_machines')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request): Response
    {
        /** @var SymfonyUserAdapter $user */
        $user = $this->getUser(); 
        $user= $user->getUser();
        $parcMachineRequest = ParcMachineFactory::makeRequest($user);
        $form=$this->createForm(ParcMachineFormType::class, $parcMachineRequest);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            try {
                $data = $form->getData();
                $this->createUseCase->__invoke($data);
                $this->addFlash('success', $this->translator->trans('parc_machines.message.create_succes'));
                return $this->redirectToRoute('app_parc_machines');
            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans("parc_machines.message.create_error"));
            }
        }
        $parcMachines = $this->findAllByUserUseCase->__invoke($user);
        return $this->render('admin/parcMachine/index.html.twig', [
            'parcMachines' => $parcMachines,
            'form' => $form->createView(),
            'is_edit' => false
        ]);
    }

    #[Route('/parcMachines/{parcMachineId}/delete', name: 'app_delete_user_parc_machine', methods:['POST'])]
    #[IsGranted('ROLE_USER')]
    public function deleteUserMachine(string $parcMachineId): Response
    {

        try {
            $machine = $this->findParcMachineByIdUseCase->__invoke(new ParcMachineId($parcMachineId));
            $this->deleteParcMachineUseCase->__invoke($machine);
            $this->addFlash('success', $this->translator->trans('parc_machines.message.delete_succes'));
        } catch (\Exception $e) {
            $this->addFlash('error', $this->translator->trans('parc_machines.message.delete_error'));
        }

        return $this->redirectToRoute('app_parc_machines');
    }
}