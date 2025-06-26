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
use Domain\Chantier\UseCase\FindChantierByUserUseCaseInterface;
use Domain\Chantier\UseCase\FindChantierByUserWithSearchUseCaseInterface;
use Domain\Chantier\UseCase\UpdateChantierUseCaseInterface;
use Domain\MachineLog\Data\Contract\CreateMachineLogRequest;
use Domain\MachineLog\UseCase\CreateMachineLogUseCaseInterface;
use Domain\User\Data\ObjectValue\UserId;
use Infrastructure\Form\Chantier\ChantierFormType;
use Infrastructure\Form\MachineLog\MachineLogFormType;
use Infrastructure\Symfony\Security\SymfonyUserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\ExpressionLanguage\Expression;
use Domain\User\Data\ObjectValue\RoleEnum;

#[Route('/dashboard')]
class ChantierController extends AbstractController
{

    public function __construct(
        private readonly FindAllChantierUseCaseInterface $findaAllChantierUseCase,
        private readonly FindChantierByIdUseCaseInterface $findChantierByIdUseCase,
        private readonly FindChantierByUserUseCaseInterface $findChantierByUserUseCase,
        private readonly FindChantierByUserWithSearchUseCaseInterface $findChantierByUserWithSearchUseCase,
        private readonly CreateChantierUseCaseInterface $createChantierUseCase,
        private readonly CreateMachineLogUseCaseInterface $CreateMachineLogUseCase,
        private readonly UpdateChantierUseCaseInterface $updateChantierUseCase,
        private readonly DeleteChantierUseCaseInterface $deleteChantierUseCase,
        private readonly TranslatorInterface $translator,
    ){}

    #[Route('/chantiers', name: 'app_chantiers', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request)
    {
        /** @var SymfonyUserAdapter $user */
        $user = $this->getUser();
        $user = $user->getUser();

        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $search = $request->query->get('search', '');

        $userId = new UserId($user->id);
        
        if (!empty($search)) {
            $chantiers = $this->findChantierByUserWithSearchUseCase->__invoke($userId, $search, $page, $limit);
            $total = $this->findChantierByUserWithSearchUseCase->getTotal($userId, $search);
        } else {
            $chantiers = $this->findChantierByUserUseCase->__invoke($userId, $page, $limit);
            $total = $this->findChantierByUserWithSearchUseCase->getTotal($userId, '');
        }
        
        // Debug temporaire
        error_log('Search: ' . $search . ', Total: ' . $total . ', Chantiers count: ' . count($chantiers));
        
        $maxPages = ceil($total / $limit); 

        return $this->render('admin/chantier/index.html.twig', [
            'chantiers' => $chantiers,
            'currentPage' => $page,
            'maxPages' => $maxPages,
            'limit' => $limit,
            'search' => $search
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

        // Correction: transformer le JSON en array avant validation
        if ($form->isSubmitted()) {
            $materialsJson = $form->get('materials')->getData();
            if (is_string($materialsJson)) {
                $materials = json_decode($materialsJson, true) ?: [];
                $form->getData()->materials = $materials;
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SymfonyUserAdapter $user  */
            $user = $this->getUser();
            
            try {
                $this->createChantierUseCase->__invoke($createChantierRequest, $user->getUser());
                $this->addFlash('success', $this->translator->trans('chantiers.messages.create_succes'));
                return $this->redirectToRoute('app_chantiers');
            } catch (\Throwable $th) {
                $this->addFlash('error', $th->getMessage());
                //redirect back
                return $this->redirectToRoute('app_chantier_create');
            }
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

        // Correction: transformer le JSON en array avant validation
        if ($form->isSubmitted()) {
            $materialsJson = $form->get('materials')->getData();
            if (is_string($materialsJson)) {
                $materials = json_decode($materialsJson, true) ?: [];
                $form->getData()->materials = $materials;
            }
        }

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $this->updateChantierUseCase->__invoke($updateRequest, $chantier);
                $this->addFlash('success', $this->translator->trans('chantiers.messages.update_succes'));
                return $this->redirectToRoute('app_chantiers');
            } catch (\Throwable $th) {
                $this->addFlash('error', $th->getMessage());
                //redirect back
                return $this->redirectToRoute('app_chantier_edit', ['chantier' => $chantier->id]);
            }
        }

        return $this->render('admin/chantier/create_update.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true,
            'errors' => $form->getErrors(true, false)
        ]);
    }

    #[Route('/chantiers/{chantier}/show', name: 'app_chantier_show', methods: ['GET', 'POST'])]
    #[IsGranted(new Expression('is_granted("ROLE_USER") or is_granted("ROLE_ADMIN")'))]
    public function show(Request $request, Chantier $chantier)
    {
        $chantier = $this->findChantierByIdUseCase->__invoke($chantier->id);
        // trouver l'utilisateur qui a créé le chantier
        $user = $chantier->user;
        $updatehantierRequest = new UpdateChantierRequest(); 
        $updateRequest = UpdateChantierFactory::makeRequest($chantier, $updatehantierRequest);

        $form = $this->createForm(ChantierFormType::class, $updateRequest, [
            'is_edit' => true,
            'data_class' => UpdateChantierRequest::class,
            'user' => $user
        ]);

        return $this->render('admin/chantier/create_update.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true,
            'errors' => $form->getErrors(true, false)
        ]);
    }

    #[Route('/chantiers/{chantier}/delete', name: 'app_chantier_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Chantier $chantier)
    {
        try {
            $this->deleteChantierUseCase->__invoke($chantier);
            $this->addFlash('success', $this->translator->trans('chantiers.messages.delete_succes'));
        } catch (\Exception $e) {
            $this->addFlash('error', $this->translator->trans('chantiers.messages.delete_error'));
        }

        return $this->redirectToRoute('app_chantiers');
    }

    #[Route('/chantiers/export', name: 'app_chantiers_export', methods: ['GET'])]
    public function export(Request $request)
    {
        $user = $this->getUser()->getUser();
        $userId = new UserId($user->id);
        $search = $request->query->get('search', '');
        
        if (!empty($search)) {
            $chantiers = $this->findChantierByUserWithSearchUseCase->__invoke($userId, $search, 1, 10000);
        } else {
            $chantiers = $this->findChantierByUserUseCase->__invoke($userId, 1, 10000);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([
            [
                'Date',
                'Nom',
                'Machine',
                'Type de surface',
                'Matériaux',
                'Niveau d\'encrassement',
                'État de vétusté',
                'Surface',
                'Durée',
                'Rendement (m²/h)',
                'Commentaire'
            ]
        ], null, 'A1');

        $row = 2;
        foreach ($chantiers as $chantier) {
            $machines = [];
            foreach ($chantier->chantierMachines as $cm) {
                $machine = $cm->parcMachine->machine;
                $machines[] = $machine->nom . ' | ' . $machine->numeroIdentification;
            }
            // Libellés pour encrassement
            $encrassementLabels = [
                1 => 'Peu sale',
                2 => 'Moyennement sale',
                3 => 'Très sale',
            ];
            $vetusteLabels = [
                1 => 'Récent',
                2 => 'État d\'usage',
                3 => 'Très ancien',
            ];
            $sheet->fromArray([
                $chantier->chantierDate->format('d/m/Y'),
                $chantier->name,
                implode(', ', $machines),
                $chantier->surfaceTypes,
                implode(', ', $chantier->materials),
                $encrassementLabels[$chantier->encrassementLevel] ?? $chantier->encrassementLevel,
                $vetusteLabels[$chantier->vetusteLevel] ?? $chantier->vetusteLevel,
                $chantier->surface,
                $chantier->duration,
                $chantier->rendement,
                $chantier->commentaire
            ], null, 'A' . $row);
            $row++;
        }

        $response = new StreamedResponse(function() use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $filename = 'chantiers_' . date('Ymd_His') . '.xlsx';
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}