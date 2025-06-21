<?php

namespace Infrastructure\Controller\Document;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Infrastructure\Symfony\Services\MultiplyRolesExpression;
use Domain\User\Data\Enum\RoleEnum;

#[Route('/dashboard')]
class DocumentsManuelsController extends AbstractController
{
    #[Route('/documents/manuels', name: 'app_documents_manuels', methods: ['GET'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function index(Request $request): Response
    {
        $manuelsPath = $this->getParameter('kernel.project_dir') . '/public/uploads/documents/manuels';
        $manuels = [];
        
        if (is_dir($manuelsPath)) {
            $files = scandir($manuelsPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
                    $manuels[] = [
                        'name' => $file,
                        'filename' => pathinfo($file, PATHINFO_FILENAME),
                        'size' => $this->formatFileSize(filesize($manuelsPath . '/' . $file)),
                        'path' => '/uploads/documents/manuels/' . $file,
                        'uploadDate' => date('d/m/Y H:i', filemtime($manuelsPath . '/' . $file))
                    ];
                }
            }
        }
        
        return $this->render('admin/documents/manuels/index.html.twig', [
            'title' => 'Manuels',
            'manuels' => $manuels,
        ]);
    }
    
    private function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
} 