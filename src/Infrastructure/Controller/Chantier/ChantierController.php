<?php

namespace Infrastructure\Controller\Chantier;

use Domain\Chantier\Data\Contract\CreateChantierRequest;
use Domain\Chantier\Data\Contract\UpdateChantierRequest;
use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Chantier\Factory\UpdateChantierFactory;
use Domain\Chantier\UseCase\CreateChantierUseCaseInterface;
use Domain\Chantier\UseCase\DeleteChantierUseCaseInterface;
use Domain\Chantier\UseCase\FindAllChantierUseCaseInterface;
use Domain\Chantier\UseCase\FindChantierByIdUseCaseInterface;
use Domain\Chantier\UseCase\UpdateChantierUseCaseInterface;
use Domain\MachineLog\Data\Contract\CreateMachineLogRequest;
use Domain\MachineLog\UseCase\CreateMachineLogUseCaseInterface;
use Infrastructure\Form\Chantier\ChantierFormType;
use Infrastructure\Form\MachineLog\MachineLogFormType;
use Infrastructure\Symfony\Security\SymfonyUserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard')]
class ChantierController extends AbstractController
{

    public function __construct(
        private readonly FindAllChantierUseCaseInterface $findaAllChantierUseCase,
        private readonly CreateChantierUseCaseInterface $createChantierUseCase,
        private readonly FindChantierByIdUseCaseInterface $findChantierByIdUseCase,
        private readonly UpdateChantierUseCaseInterface $updateChantierUseCase,
        private readonly DeleteChantierUseCaseInterface $deleteChantierUseCase,
        private readonly CreateMachineLogUseCaseInterface $CreateMachineLogUseCase
    ){}

    #[Route('/chantiers', name: 'app_chantiers', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index()
    {
        $chantiers = $this->findaAllChantierUseCase->__invoke();

        return $this->render('admin/chantier/index.html.twig', [
            'chantiers' => $chantiers
        ]);
    }

    #[Route('/chantiers/create', name: 'app_chantier_create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request)
    {
        $createChantierRequest = new CreateChantierRequest();
       
        /** @var SymfonyUserAdapter $user */
        $user = $this->getUser();

        $form = $this->createForm(ChantierFormType::class, $createChantierRequest, [
            'is_edit' => false,
            'data_class' => CreateChantierRequest::class,
            'user' => $user->getUser()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SymfonyUserAdapter $user  */
            $user = $this->getUser();
            $this->createChantierUseCase->__invoke($createChantierRequest, $user->getUser());

            $this->addFlash('success', 'Nouveau chantier créé');
            return $this->redirectToRoute('app_chantiers');
        }
        return $this->render('admin/chantier/create_update.html.twig', [
            'form' => $form->createView(),
            'is_edit' => false,
            'errors' => $form->getErrors(true, false)
        ]);
    }

    #[Route('/chantiers/{chantier}/edit', name: 'app_chantier_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function update(Request $request, Chantier $chantier)
    {
         /** @var SymfonyUserAdapter $user */
         $user = $this->getUser();

        $chantier = $this->findChantierByIdUseCase->__invoke($chantier->id);
        $updatehantierRequest = new UpdateChantierRequest(); 
        $updateRequest = UpdateChantierFactory::makeRequest($chantier, $updatehantierRequest);

        $form = $this->createForm(ChantierFormType::class, $updateRequest, [
            'is_edit' => true,
            'data_class' => UpdateChantierRequest::class,
            'user' => $user->getUser()
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->updateChantierUseCase->__invoke($updateRequest, $chantier);
            $this->addFlash('success', 'Chantier modifie');
            return $this->redirectToRoute('app_chantiers');
        }

        return $this->render('admin/chantier/create_update.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true,
            'errors' => $form->getErrors(true, false)
        ]);
    }

    #[Route('/chantiers/{chantier}/show', name: 'app_chantier_show', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function show(Request $request, Chantier $chantier)
    {
        $chantier = $this->findChantierByIdUseCase->__invoke($chantier->id);
        $parcMachinesChantier = [];
        foreach ($chantier->chantierMachines as $chantierMachine) {
            $parcMachinesChantier[] = $chantierMachine->parcMachine;
        }
        $machineLogRequest = new CreateMachineLogRequest();
        $machineLogForm = $this->createForm(MachineLogFormType::class, $machineLogRequest, [
            'parcMachines' => $parcMachinesChantier
        ]);
    
        $machineLogForm->handleRequest($request);
    
        if ($machineLogForm->isSubmitted() && $machineLogForm->isValid()) {
            $this->CreateMachineLogUseCase->__invoke($machineLogForm->getData(), $chantier);
            $this->addFlash('success', 'Activité(s) enregistrée(s)');
            return $this->redirectToRoute('app_chantier_show', ['chantier'=>$chantier->id]);
        }
    
        return $this->render('admin/chantier/show.html.twig', [
            'chantier' => $chantier,
            'machineLogForm' => $machineLogForm->createView()
        ]);
    }

    #[Route('/chantiers/{chantier}/delete', name: 'app_chantier_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Chantier $chantier)
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete' . $chantier->id, $submittedToken)) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_chantiers');
        }

        try {
            $this->deleteChantierUseCase->__invoke($chantier);
            $this->addFlash('success', 'Chantier supprimé avec succès');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la suppression de la chantier');
        }

        return $this->redirectToRoute('app_chantiers');
    }
}