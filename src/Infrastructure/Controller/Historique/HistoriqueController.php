<?php

namespace Infrastructure\Controller\Historique;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Infrastructure\Symfony\Services\MultiplyRolesExpression;
use Domain\User\Data\Enum\RoleEnum;
use Domain\User\UseCase\FindUserByIdUseCaseInterface;
use Domain\User\Data\ObjectValue\UserId;
use Domain\Entretien\Gateway\EntretienLogRepositoryInterface;
use Domain\Chantier\UseCase\FindAllChantierUseCaseInterface;
use Domain\Chantier\UseCase\FindAllChantierWithSearchUseCaseInterface;
use Infrastructure\Symfony\Security\SymfonyUserAdapter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#[Route('/dashboard')]
class HistoriqueController extends AbstractController
{
    public function __construct(
        private readonly FindUserByIdUseCaseInterface $findUserByIdUseCase,
        private readonly EntretienLogRepositoryInterface $entretienLogRepository,
        private readonly FindAllChantierUseCaseInterface $findAllChantierUseCase,
        private readonly FindAllChantierWithSearchUseCaseInterface $findAllChantierWithSearchUseCase
    ) {}

    #[Route('/historique/entretiens', name: 'app_historique_entretiens', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function entretiens(Request $request): Response
    {
        /** @var SymfonyUserAdapter $user */
        $user = $this->getUser();
        $currentUser = $this->findUserByIdUseCase->__invoke(new UserId($user->getId()));
        
        // Récupérer tous les logs d'entretien de l'utilisateur
        $entretienLogs = $this->entretienLogRepository->findAllByUser($currentUser);
        
        return $this->render('admin/historique/entretiens/index.html.twig', [
            'title' => 'Historique des entretiens',
            'entretienLogs' => $entretienLogs,
        ]);
    }

    #[Route('/historique/chantiers', name: 'app_historique_chantiers', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function chantiers(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $search = $request->query->get('search', '');
        
        if (!empty($search)) {
            // Utiliser le service de recherche globale sans filtrage utilisateur
            $chantiers = $this->findAllChantierWithSearchUseCase->__invoke($search, $page, $limit);
            $total = $this->findAllChantierWithSearchUseCase->getTotal($search);
        } else {
            // Récupérer tous les chantiers sans filtrage utilisateur avec pagination
            $chantiers = $this->findAllChantierUseCase->__invoke($page, $limit);
            $total = $this->findAllChantierUseCase->getTotal();
        }
        
        $maxPages = ceil($total / $limit); 
        
        return $this->render('admin/historique/chantiers/index.html.twig', [
            'title' => 'Historique des chantiers',
            'chantiers' => $chantiers,
            'currentPage' => $page,
            'maxPages' => $maxPages,
            'limit' => $limit,
            'search' => $search
        ]);
    }

    #[Route('/historique/chantiers/export', name: 'app_historique_chantiers_export', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function exportChantiers(Request $request): StreamedResponse
    {
        $search = $request->query->get('search', '');
        
        if (!empty($search)) {
            $chantiers = $this->findAllChantierWithSearchUseCase->__invoke($search, 1, 10000);
        } else {
            $chantiers = $this->findAllChantierUseCase->__invoke(1, 10000);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([
            [
                'Date',
                'Nom',
                'Client',
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
                $chantier->user->firstname . ' ' . $chantier->user->lastname,
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

        $filename = 'historique_chantiers_' . date('Ymd_His') . '.xlsx';
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
} 