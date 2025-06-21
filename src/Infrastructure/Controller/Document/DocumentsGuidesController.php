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
class DocumentsGuidesController extends AbstractController
{
    #[Route('/documents/guides', name: 'app_documents_guides', methods: ['GET'])]
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function index(Request $request): Response
    {
        $guidessPath = $this->getParameter('kernel.project_dir') . '/public/uploads/documents/guides';
        $guides = [];
        
        if (is_dir($guidessPath)) {
            $files = scandir($guidessPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
                    $guides[] = [
                        'name' => $file,
                        'filename' => pathinfo($file, PATHINFO_FILENAME),
                        'size' => $this->formatFileSize(filesize($guidessPath . '/' . $file)),
                        'path' => '/uploads/documents/guides/' . $file,
                        'uploadDate' => date('d/m/Y H:i', filemtime($guidessPath . '/' . $file))
                    ];
                }
            }
        }

        return $this->render('admin/documents/guides/index.html.twig', [
            'title' => ' Guides',
            'guides' => $guides,
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