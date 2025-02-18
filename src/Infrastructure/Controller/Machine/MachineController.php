<?php

namespace Infrastructure\Controller\Machine;

use Domain\Machine\Data\Contract\CreateMachineRequest;
use Domain\User\Data\Enum\RoleEnum;
use Domain\Machine\UseCase\CreateMachineUseCaseInterface;
use Infrastructure\Form\Machine\MachineFormType ;
use Infrastructure\Symfony\Services\MultiplyRolesExpression;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard')]
class MachineController extends AbstractController
{
    public function __construct(
      private readonly  CreateMachineUseCaseInterface $useCase
    ){}
    #[Route('/machine/create', name: 'app_create_machine')]
    #[IsGranted(new MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function create(Request $request)
    {
        $form=$this->createForm(MachineFormType::class, new CreateMachineRequest());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $this->useCase->__invoke($data);

            return $this->redirectToRoute('app_create_machine');
        }

        return $this->render('admin/Machine/create.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}