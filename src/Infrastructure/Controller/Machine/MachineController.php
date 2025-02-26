<?php

namespace Infrastructure\Controller\Machine;

use Domain\Machine\Data\Contract\CreateMachineRequest;
use Domain\Machine\UseCase\CreateMachineUseCaseInterface;
use Domain\Machine\UseCase\FindAllMachineUseCaseInterface;
use Domain\Machine\UseCase\FindMachineByIdUseCaseInterface;
use Infrastructure\Form\Machine\MachineFormType ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Domain\Machine\Data\ObjectValue\MachineId;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Domain\Machine\Factory\MachineFactory;
use Domain\Machine\Data\Contract\UpdateMachineRequest;
use Domain\Machine\UseCase\DeleteMachineUseCase;
use Domain\Machine\UseCase\UpdateMachineUseCaseInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

#[Route('/dashboard')]
class MachineController extends AbstractController
{
    public function __construct(
        private readonly  CreateMachineUseCaseInterface $useCase,
        private readonly FindAllMachineUseCaseInterface $findAllUseCase,
        private readonly FindMachineByIdUseCaseInterface $findByIdUseCase,
        private readonly UpdateMachineUseCaseInterface $updateUseCase,
        private readonly DeleteMachineUseCase $deleteUseCase,
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
                $this->addFlash('success', 'Machine créée avec succès');
                return $this->redirectToRoute('app_machines');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la création de Machine');
            }
        }

        return $this->render('admin/machine/create.html.twig',[
            'form' => $form->createView(),
            'is_edit' => false,
        ]);
    }

    #[Route('/machine/{id}/edit', name:'app_update_machine', methods:['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(Request $request, string $id): Response
    {
        $machine = $this->findByIdUseCase->__invoke(new MachineId($id));
        $updateMachineRequest = MachineFactory::makeFromMachine($machine);
        $form = $this->createForm(MachineFormType::class, $updateMachineRequest, [
            'is_edit' => true,
            'data_class' => UpdateMachineRequest::class,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->updateUseCase->__invoke(new MachineId($id), $updateMachineRequest);
                $this->addFlash('success', 'Machine mis à jour avec succès');
                return $this->redirectToRoute('app_machines');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la mise à jour de la machine');
            }
        }

        return $this->render('admin/machine/create.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true,
            'machine' => $machine,
        ]);
    }

    #[Route('/machine/{machineId}/delete', name:'app_delete_machine', methods:['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(string $machineId): Response
    {
        try {
            $this->deleteUseCase->__invoke(new MachineId($machineId));
            $this->addFlash('success', 'Machine supprimée avec succès');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la suppression de la machine');
        }

        return $this->redirectToRoute('app_machines');
    }

    #[Route('/machine/{machineId}/download', name: 'app_download_machine_fiche_technique', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function download(string $machineId): ?Response
    {
        try {
            $machine = $this->findByIdUseCase->__invoke(new MachineId($machineId));

            if (!$machine->ficheTechnique) {
                $this->addFlash('info', 'Aucune fiche technique disponible.');
                return $this->redirectToRoute('app_machines'); 
            }
            $file = MachineFactory::getFilecontent($machine->ficheTechnique);
            if (!$file) {
                $this->addFlash('info', 'Le fichier n\'existe pas.');
                return $this->redirectToRoute('app_machines'); 
            }
            $response = new BinaryFileResponse($file->getRealPath());
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'Fiche_' . $machine->nom . '.' . $file->guessExtension()
            );
            return $response;
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors du téléchargement de la fiche technique.');
            return $this->redirectToRoute('app_machines'); 
        }
    }
}