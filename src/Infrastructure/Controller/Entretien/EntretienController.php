<?php

namespace Infrastructure\Controller\Entretien;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Infrastructure\Symfony\Services\MultiplyRolesExpression;
use Domain\User\Data\Enum\RoleEnum;
use Domain\Entretien\Data\Contract\CreateEntretienLogRequest;
use Domain\Entretien\UseCase\CreateEntretienLogUseCaseInterface;
use Domain\Entretien\Gateway\EntretienLogRepositoryInterface;
use Domain\Entretien\Factory\EntretienLogFactory;
use Domain\Entretien\Data\ObjectValue\EntretienLogId;
use Domain\User\UseCase\FindUserByIdUseCaseInterface;
use Domain\User\Data\ObjectValue\UserId;
use Domain\Machine\UseCase\FindAllMachineByUserUseCaseInterface;
use Domain\ParcMachine\UseCase\FindAllParcMachineByUserUseCaseInterface;
use Infrastructure\Form\Entretien\EntretienLogFormType;
use Infrastructure\Symfony\Security\SymfonyUserAdapter;

#[Route('/dashboard')]
class EntretienController extends AbstractController
{
    public function __construct(
        private readonly CreateEntretienLogUseCaseInterface $createEntretienLogUseCase,
        private readonly FindUserByIdUseCaseInterface $findUserByIdUseCase,
        private readonly FindAllMachineByUserUseCaseInterface $findAllMachineByUserUseCase,
        private readonly FindAllParcMachineByUserUseCaseInterface $findAllParcMachineByUserUseCase,
        private readonly EntretienLogRepositoryInterface $entretienLogRepository
    ) {}

    #[Route('/entretiens/quotidien', name: 'app_entretiens_quotidien', methods: ['GET'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function quotidien(Request $request): Response
    {
        return $this->render('admin/entretien/quotidien/index.html.twig', [
            'title' => 'Activités quotidiennes et hebdomadaires',
        ]);
    }

    #[Route('/entretiens/regulier', name: 'app_entretiens_regulier', methods: ['GET'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function regulier(Request $request): Response
    {
        /** @var SymfonyUserAdapter $user */
        $user = $this->getUser();
        $currentUser = $this->findUserByIdUseCase->__invoke(new UserId($user->getId()));
        
        // Récupérer les machines du parc de l'utilisateur
        $parcMachines = $this->findAllParcMachineByUserUseCase->__invoke($currentUser);
        
        return $this->render('admin/entretien/regulier/index.html.twig', [
            'title' => 'Entretien régulier machine',
            'parcMachines' => $parcMachines,
        ]);
    }

    #[Route('/entretiens/ponctuel', name: 'app_entretiens_ponctuel', methods: ['GET'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function ponctuel(Request $request): Response
    {
        /** @var SymfonyUserAdapter $user */
        $user = $this->getUser();
        $currentUser = $this->findUserByIdUseCase->__invoke(new UserId($user->getId()));
        
        // Récupérer les machines du parc de l'utilisateur
        $parcMachines = $this->findAllParcMachineByUserUseCase->__invoke($currentUser);
        
        return $this->render('admin/entretien/ponctuel/index.html.twig', [
            'title' => 'Entretien ponctuel',
            'parcMachines' => $parcMachines,
        ]);
    }

    #[Route('/entretiens/aides', name: 'app_entretiens_aides', methods: ['GET'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function aides(Request $request): Response
    {
        // Récupérer les documents PDF qui commencent par "E0"
        $documentsPath = $this->getParameter('kernel.project_dir') . '/public/uploads/documents';
        $documents = [];
        
        if (is_dir($documentsPath)) {
            $files = scandir($documentsPath);
            foreach ($files as $file) {
                if (is_file($documentsPath . '/' . $file) && 
                    pathinfo($file, PATHINFO_EXTENSION) === 'pdf' && 
                    str_starts_with($file, 'E0')) {
                    
                    // Extraire le numéro et le titre du document
                    $filename = pathinfo($file, PATHINFO_FILENAME);
                    $number = substr($filename, 0, 4); // E001, E002, etc.
                    $title = substr($filename, 4); // Le reste du nom
                    
                    $documents[] = [
                        'filename' => $file,
                        'number' => $number,
                        'title' => trim($title),
                        'path' => 'uploads/documents/' . $file
                    ];
                }
            }
            
            // Trier les documents par numéro
            usort($documents, function($a, $b) {
                return strcmp($a['number'], $b['number']);
            });
        }
        
        return $this->render('admin/entretien/aides/index.html.twig', [
            'title' => 'Liste des aides à l\'entretien',
            'documents' => $documents,
        ]);
    }

    #[Route('/entretiens/log/create', name: 'app_entretien_log_create', methods: ['POST'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function createLog(Request $request): JsonResponse
    {
        $user = $this->getUser();
        $currentUser = $this->findUserByIdUseCase->__invoke(new UserId($user->getId()));

        $form = $this->createForm(EntretienLogFormType::class, new CreateEntretienLogRequest(), [
            'user' => $currentUser
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                
                $entretienLog = $this->createEntretienLogUseCase->__invoke($data);
                
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Log d\'entretien enregistré avec succès',
                    'logId' => $entretienLog->getId()->getValue()
                ]);
            } catch (\Exception $e) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Erreur lors de l\'enregistrement du log d\'entretien: ' . $e->getMessage()
                ], 500);
            }
        }
        
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        
        return new JsonResponse([
            'success' => false,
            'message' => 'Erreurs de validation',
            'errors' => $errors
        ], 400);
    }

    #[Route('/entretiens/log/modal', name: 'app_entretien_log_modal', methods: ['GET'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function getModal(Request $request): Response
    {
        $user = $this->getUser();
        $currentUser = $this->findUserByIdUseCase->__invoke(new UserId($user->getId()));

        $form = $this->createForm(EntretienLogFormType::class, new CreateEntretienLogRequest(), [
            'user' => $currentUser
        ]);

        return $this->render('admin/entretien/modal/create_log.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/entretiens/maintenance-table/log', name: 'app_entretien_maintenance_table_log', methods: ['POST'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function createMaintenanceTableLog(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Données invalides'
                ], 400);
            }

            // Validation des données requises
            if (empty($data['hours']) || empty($data['date']) || empty($data['tasks']) || !is_array($data['tasks']) || empty($data['machineId'])) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Données manquantes: heures, date, tâches et ID de machine sont requis'
                ], 400);
            }

            $user = $this->getUser();
            $currentUser = $this->findUserByIdUseCase->__invoke(new UserId($user->getId()));

            // Récupérer les machines du parc de l'utilisateur
            $parcMachines = $this->findAllParcMachineByUserUseCase->__invoke($currentUser);
            
            if (empty($parcMachines)) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Aucune machine trouvée pour cet utilisateur'
                ], 400);
            }

            // Trouver la machine spécifique sélectionnée
            $selectedParcMachine = null;
            foreach ($parcMachines as $parcMachine) {
                if ($parcMachine->getId()->getValue() === $data['machineId']) {
                    $selectedParcMachine = $parcMachine;
                    break;
                }
            }

            if (!$selectedParcMachine) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Machine sélectionnée non trouvée dans votre parc'
                ], 400);
            }

            // Créer un log pour chaque tâche sélectionnée
            $createdLogs = [];
            foreach ($data['tasks'] as $task) {
                try {
                    // Créer directement l'objet EntretienLog avec ParcMachine
                    $entretienLog = new \Domain\Entretien\Data\Model\EntretienLog(
                        \Domain\Entretien\Data\ObjectValue\EntretienLogId::make(),
                        $selectedParcMachine,
                        new \DateTime($data['date']),
                        (int) $data['hours'],
                        $task['name'],
                        new \DateTimeImmutable(),
                        null
                    );
                    
                    $savedLog = $this->entretienLogRepository->save($entretienLog);
                    $createdLogs[] = $savedLog->getId()->getValue();
                } catch (\Exception $e) {
                    // Log l'erreur détaillée pour le débogage
                    error_log('Erreur détaillée lors de la création du log pour la tâche ' . $task['name'] . ': ' . $e->getMessage());
                    error_log('Stack trace: ' . $e->getTraceAsString());
                    continue;
                }
            }

            if (empty($createdLogs)) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Aucun log n\'a pu être créé'
                ], 500);
            }

            $machine = $selectedParcMachine->getMachine();
            return new JsonResponse([
                'success' => true,
                'message' => count($createdLogs) . ' log(s) d\'entretien enregistré(s) avec succès pour la machine ' . $machine->getNom(),
                'logIds' => $createdLogs
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/entretiens/maintenance-table/logs', name: 'app_entretien_maintenance_table_logs', methods: ['GET'])]
    #[IsGranted(new MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function getMaintenanceTableLogs(Request $request): JsonResponse
    {
        try {
            $user = $this->getUser();
            $currentUser = $this->findUserByIdUseCase->__invoke(new UserId($user->getId()));

            // Récupérer l'ID de la machine depuis les paramètres de requête
            $machineId = $request->query->get('machineId');
            
            if (!$machineId) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'ID de machine requis'
                ], 400);
            }

            // Récupérer les machines du parc de l'utilisateur pour trouver la machine spécifique
            $parcMachines = $this->findAllParcMachineByUserUseCase->__invoke($currentUser);
            
            $selectedMachine = null;
            foreach ($parcMachines as $parcMachine) {
                if ($parcMachine->getId()->getValue() === $machineId) {
                    $selectedMachine = $parcMachine->getMachine();
                    break;
                }
            }

            if (!$selectedMachine) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Machine non trouvée dans votre parc'
                ], 400);
            }

            // Récupérer les logs d'entretien filtrés par machine
            $logs = $this->entretienLogRepository->findAllByUserAndMachine($currentUser, $selectedMachine);
            
            $logsData = [];
            foreach ($logs as $log) {
                $parcMachine = $log->getParcMachine();
                $machine = $parcMachine->getMachine();
                
                $logsData[] = [
                    'id' => $log->getId()->getValue(),
                    'parcMachineId' => $parcMachine->getId()->getValue(),
                    'machineId' => $machine->getId()->getValue(),
                    'machineName' => $machine->getNom(),
                    'machineNumber' => $machine->getNumeroIdentification(),
                    'date' => $log->getLogDate()->format('Y-m-d'),
                    'hours' => $log->getVolumeHoraire(),
                    'activity' => $log->getActivite(),
                    'createdAt' => $log->getCreatedAt()->format('Y-m-d H:i:s')
                ];
            }

            return new JsonResponse([
                'success' => true,
                'logs' => $logsData
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur lors de la récupération des logs: ' . $e->getMessage()
            ], 500);
        }
    }
} 