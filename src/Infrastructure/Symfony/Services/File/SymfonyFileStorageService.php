<?php

namespace Infrastructure\Symfony\Services\File;

use Domain\Shared\Data\ObjectValue\FileInterface;
use Domain\Shared\Service\File\FileStorageServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class SymfonyFileStorageService implements FileStorageServiceInterface
{
    private string $uploadDir;
    private string $publicDir;

    /**
     * @param string $uploadDir Absolute path to upload directory
     * @param string $publicDir Public accessible path prefix (for URLs)
     */
    public function __construct(string $uploadDir, string $publicDir = '/uploads')
    {
        $this->uploadDir = rtrim($uploadDir, '/\\');
        $this->publicDir = rtrim($publicDir, '/');
    }

    /**
     * Store a file in the specified folder
     * 
     * @param FileInterface $file The file to store
     * @param string $folder The subfolder to store the file in (relative to upload dir)
     * @return string The public URL to access the file
     */
    public function store(FileInterface $file, string $folder): string
    {
        // Normalize folder path
        $folder = trim($folder, '/\\');
        $targetDir = $this->uploadDir . DIRECTORY_SEPARATOR . $folder;

        // Create directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Generate unique filename
        $extension = $this->getExtensionFromMime($file->getMimeType());
        $filename = uniqid() . '.' . $extension;
        $fullPath = $targetDir . DIRECTORY_SEPARATOR . $filename;
        
        try {
            if ($file instanceof SymfonyFileAdapter) {
                $uploadedFile = $file->getUnderlyingFile();
                
                try {
                    // Try to move the file
                    $uploadedFile->move($targetDir, $filename);
                } catch (FileException $e) {
                    // If moving fails, we'll fall back to writing the content
                    file_put_contents($fullPath, $file->getContent());
                }
            } else {
                // For non-Symfony files, write the content directly
                file_put_contents($fullPath, $file->getContent());
            }
            
            // Return public URL (not file system path)
            return $this->publicDir . '/' . $folder . '/' . $filename;
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to store file: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Retrieve a file by its path
     * 
     * @param string $path The public path of the file (without public dir prefix)
     * @return FileInterface|null The file or null if not found
     */
    public function retrieve(string $path): ?FileInterface
    {
        // Remove public dir prefix if present
        if (strpos($path, $this->publicDir) === 0) {
            $path = substr($path, strlen($this->publicDir));
        }
        
        $path = ltrim($path, '/\\');
        $absolutePath = $this->uploadDir . DIRECTORY_SEPARATOR . $path;
        
        if (!file_exists($absolutePath)) {
            return null;
        }
        
        try {
            $uploadedFile = new UploadedFile(
                $absolutePath, 
                basename($absolutePath),
                mime_content_type($absolutePath) ?: 'application/octet-stream', 
                null, 
                true // Test mode true so we don't check if it was uploaded via HTTP
            );
            
            return new SymfonyFileAdapter($uploadedFile);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Delete a file by its path
     * 
     * @param string $path The public path of the file
     */
    public function delete(string $path): void
    {
        // Remove public dir prefix if present
        if (strpos($path, $this->publicDir) === 0) {
            $path = substr($path, strlen($this->publicDir));
        }
        
        $path = ltrim($path, '/\\');
        $absolutePath = $this->uploadDir . DIRECTORY_SEPARATOR . $path;
        
        if (file_exists($absolutePath) && is_file($absolutePath)) {
            unlink($absolutePath);
        }
    }

    /**
     * Get file extension from MIME type
     * 
     * @param string $mimeType The MIME type
     * @return string The file extension
     */
    private function getExtensionFromMime(string $mimeType): string
    {
        $map = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'application/pdf' => 'pdf',
            'text/plain' => 'txt',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/zip' => 'zip',
        ];
        
        return $map[$mimeType] ?? 'bin';
    }
}