<?php

namespace Infrastructure\Controller\Document;

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
class DocumentsDepannageController extends AbstractController
{
    #[Route('/depannage/fiches-aides', name: 'app_depannage_aides', methods: ['GET'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function fichesAide(Request $request): Response
    {
        // Récupérer les documents PDF qui commencent par "E0"
        $documentsPath = $this->getParameter('kernel.project_dir') . '/public/uploads/documents/depannage';
        $documents = [];
        
        if (is_dir($documentsPath)) {
            $files = scandir($documentsPath);
            foreach ($files as $file) {
                if (is_file($documentsPath . '/' . $file) && 
                    pathinfo($file, PATHINFO_EXTENSION) === 'pdf' && 
                    str_starts_with($file, 'D0')) {
                    
                    // Extraire le numéro et le titre du document
                    $filename = pathinfo($file, PATHINFO_FILENAME);
                    $number = substr($filename, 0, 4); // D001, D002, etc.
                    $title = substr($filename, 4); // Le reste du nom
                    
                    $documents[] = [
                        'filename' => $file,
                        'number' => $number,
                        'title' => trim($title),
                        'path' => 'uploads/documents/depannage/' . $file
                    ];
                }
            }
            
            // Trier les documents par numéro
            usort($documents, function($a, $b) {
                return strcmp($a['number'], $b['number']);
            });
        }
        
        return $this->render('admin/entretien/aides/index.html.twig', [
            'title' => 'Liste des documents de dépannage',
            'documents' => $documents,
        ]);
    }

    #[Route('/depannage/documents-techniciens', name: 'app_depannage_documents_techniciens', methods: ['GET'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function documentsTechniciens(Request $request): Response
    {
        // Récupérer les documents PDF qui commencent par "E0"
        $documentsPath = $this->getParameter('kernel.project_dir') . '/public/uploads/documents/depannage';
        $documents = [];
        
        if (is_dir($documentsPath)) {
            $files = scandir($documentsPath);
            foreach ($files as $file) {
                if (is_file($documentsPath . '/' . $file) && 
                    pathinfo($file, PATHINFO_EXTENSION) === 'pdf' && 
                    str_starts_with($file, 'DT')) {
                    
                    // Extraire le numéro et le titre du document
                    $filename = pathinfo($file, PATHINFO_FILENAME);
                    $number = substr($filename, 0, 4); // DT001, DT002, etc.
                    $title = substr($filename, 4); // Le reste du nom
                    
                    $documents[] = [
                        'filename' => $file,
                        'number' => $number,
                        'title' => trim($title),
                        'path' => 'uploads/documents/depannage/' . $file
                    ];
                }
            }
            
            // Trier les documents par numéro
            usort($documents, function($a, $b) {
                return strcmp($a['number'], $b['number']);
            });
        }
        
        return $this->render('admin/entretien/aides/index.html.twig', [
            'title' => 'Liste des documents de dépannage pour techniciens',
            'documents' => $documents,
        ]);
    }
} 