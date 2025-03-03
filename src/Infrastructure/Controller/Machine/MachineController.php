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
use Symfony\Contracts\Translation\TranslatorInterface;
use Domain\Machine\UseCase\DeleteMachineUseCaseInterface;
use Domain\Machine\UseCase\UpdateMachineUseCaseInterface;

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
                /** @var CreateMachineRequest $data */
                $data = $form->getData();
                $document = $this->uploadFileUseCase->__invoke($data->ficheTechnique);
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

        return $this->render('admin/machine/create.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true,
            'machine' => $machine,
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