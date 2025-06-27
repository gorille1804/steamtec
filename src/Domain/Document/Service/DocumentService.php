<?php

namespace Domain\Document\Service;

use Domain\Shared\Data\ObjectValue\FileInterface;

class DocumentService
{
    /**
     * Obtient la taille d'un fichier sous forme de chaîne formatée
     *
     * @param FileInterface $file
     * @return string
     */
    public function getFormattedFileSize(FileInterface $file): string
    {
        // Use content length as fallback if getSize is not available
        try {
            // Try to get the size directly if the method exists
            if (method_exists($file, 'getSize')) {
                $bytes = $file->getSize();
            } else {
                // Fallback to content length
                $bytes = strlen($file->getContent());
            }
        } catch (\Exception $e) {
            // If even that fails, assume a minimal size
            $bytes = 0;
        }
        
        if ($bytes < 1024) {
            return $bytes . ' B';
        } elseif ($bytes < 1048576) {
            return round($bytes / 1024, 2) . ' KB';
        } elseif ($bytes < 1073741824) {
            return round($bytes / 1048576, 2) . ' MB';
        } else {
            return round($bytes / 1073741824, 2) . ' GB';
        }
    }
    
    /**
     * Génère un URI unique pour un fichier
     *
     * @param string $folderPath
     * @param string $extension
     * @return string
     */
    public function generateUniqueUri(string $folderPath, string $extension): string
    {
        return "/uploads/" . $folderPath . '/' . uniqid() . '.' . $extension;
    }
    
    /**
     * Obtient l'extension à partir du type MIME
     *
     * @param string $mimeType
     * @return string
     */
    public function getExtensionFromMime(string $mimeType): string
    {
        $map = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'application/pdf' => 'pdf',
            'text/plain' => 'txt',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            // Ajoutez d'autres types MIME si nécessaire
        ];
        
        return $map[$mimeType] ?? 'bin';
    }
}