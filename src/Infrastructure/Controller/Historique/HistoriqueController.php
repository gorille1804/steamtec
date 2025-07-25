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
        $page = $request->query->getInt('page', 1);
        $limit = 30;
        $search = $request->query->get('search', '');
        
        if (!empty($search)) {
            // Utiliser le service de recherche
            $entretienLogs = $this->entretienLogRepository->findAllWithSearch($search, $page, $limit);
            $total = $this->entretienLogRepository->getTotalCountWithSearch($search);
        } else {
            // Récupérer tous les logs d'entretien de tous les utilisateurs avec pagination
            $entretienLogs = $this->entretienLogRepository->findAllPaginated($page, $limit);
            $total = $this->entretienLogRepository->getTotalCount();
        }
        
        // Charger les fichiers JSON de maintenance
        $ponctuelPath = $this->getParameter('kernel.project_dir') . '/public/assets/data/ponctuel-maintenance-data.json';
        $tablePath = $this->getParameter('kernel.project_dir') . '/public/assets/data/maintenance-table-data.json';
        $ponctuelData = file_exists($ponctuelPath) ? json_decode(file_get_contents($ponctuelPath), true) : [];
        $tableData = file_exists($tablePath) ? json_decode(file_get_contents($tablePath), true) : [];
        $taskMapping = [];
        if (isset($ponctuelData['task_mapping'])) {
            $taskMapping = array_merge($taskMapping, $ponctuelData['task_mapping']);
        }
        if (isset($tableData['task_mapping'])) {
            $taskMapping = array_merge($taskMapping, $tableData['task_mapping']);
        }

        // Enrichir chaque log avec le nom lisible de l'activité
        foreach ($entretienLogs as $log) {
            $activiteKey = $log->getActivite();
            $activiteLabel = $taskMapping[$activiteKey]['name'] ?? $activiteKey;
            // Ajout dynamique d'une propriété pour la vue
            $log->activiteLabel = $activiteLabel;
        }
        
        $maxPages = ceil($total / $limit);
        
        return $this->render('admin/historique/entretiens/index.html.twig', [
            'title' => 'Historique des entretiens',
            'entretienLogs' => $entretienLogs,
            'currentPage' => $page,
            'maxPages' => $maxPages,
            'limit' => $limit,
            'total' => $total,
            'search' => $search
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

    #[Route('/historique/entretiens/export', name: 'app_historique_entretiens_export', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function exportEntretiens(Request $request): StreamedResponse
    {
        $search = $request->query->get('search', '');
        
        if (!empty($search)) {
            $entretienLogs = $this->entretienLogRepository->findAllWithSearch($search, 1, 10000);
        } else {
            $entretienLogs = $this->entretienLogRepository->findAllPaginated(1, 10000);
        }

        // Charger les fichiers JSON de maintenance
        $ponctuelPath = $this->getParameter('kernel.project_dir') . '/public/assets/data/ponctuel-maintenance-data.json';
        $tablePath = $this->getParameter('kernel.project_dir') . '/public/assets/data/maintenance-table-data.json';
        $ponctuelData = file_exists($ponctuelPath) ? json_decode(file_get_contents($ponctuelPath), true) : [];
        $tableData = file_exists($tablePath) ? json_decode(file_get_contents($tablePath), true) : [];
        $taskMapping = [];
        if (isset($ponctuelData['task_mapping'])) {
            $taskMapping = array_merge($taskMapping, $ponctuelData['task_mapping']);
        }
        if (isset($tableData['task_mapping'])) {
            $taskMapping = array_merge($taskMapping, $tableData['task_mapping']);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([
            [
                'Date',
                'Utilisateur',
                'Machine',
                'Numéro d\'identification',
                'Volume',
                'Activité',
                'Créé le'
            ]
        ], null, 'A1');

        $row = 2;
        foreach ($entretienLogs as $log) {
            $parcMachine = $log->getParcMachine();
            $user = $parcMachine->getUser();
            $machine = $parcMachine->getMachine();

            // Correspondance activité : clé technique -> nom lisible
            $activiteKey = $log->getActivite();
            $activiteLabel = $taskMapping[$activiteKey]['name'] ?? $activiteKey;
            
            $sheet->fromArray([
                $log->getLogDate()->format('d/m/Y'),
                $user->getFirstname() . ' ' . $user->getLastname(),
                $machine->getNom(),
                $machine->getNumeroIdentification(),
                $log->getVolumeHoraire() . ($log->isYear() ? ' an' . ($log->getVolumeHoraire() > 1 ? 's' : '') : ' h'),
                $activiteLabel,
                $log->getCreatedAt()->format('d/m/Y H:i')
            ], null, 'A' . $row);
            $row++;
        }

        $response = new StreamedResponse(function() use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $filename = 'historique_entretiens_' . date('Ymd_His') . '.xlsx';
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
} 