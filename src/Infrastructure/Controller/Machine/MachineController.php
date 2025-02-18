<?php

namespace Infrastructure\Controller\Machine;

use Domain\Machine\Data\Contract\CreateMachineRequest;
use Domain\Machine\UseCase\CreateMachineUseCaseInterface;
use Domain\Machine\UseCase\FindAllMachineUseCaseInterface;
use Infrastructure\Form\Machine\MachineFormType ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard')]
class MachineController extends AbstractController
{
    public function __construct(
        private readonly  CreateMachineUseCaseInterface $useCase,
        private readonly FindAllMachineUseCaseInterface $findAllUseCase,
    ){}

    #[Route('/machines', name: 'app_machines')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        $machines = $this->findAllUseCase->__invoke();
        return $this->render('admin/machine/index.html.twig', [
            'machines' => $machines,
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
                $data = $form->getData();
                $this->useCase->__invoke($data);
                $this->addFlash('success', 'Utilisateur créé avec succès');
                return $this->redirectToRoute('app_machines');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la création de l\'utilisateur');
            }
        }

        return $this->render('admin/Machine/create.html.twig',[
            'form' => $form->createView(),
            'is_edit' => false,
        ]);
    }
}