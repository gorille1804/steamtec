<?php

namespace Infrastructure\Symfony\Services\Document;

use Domain\Document\Service\DocumentDownloaderInterface;
use Domain\Document\Data\Model\Document;
use Domain\Document\Service\DocumentDeleteInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Filesystem\Filesystem;

class SymfonyDocumentDownloader implements DocumentDownloaderInterface, DocumentDeleteInterface
{
    private string $projectDir;
    private Filesystem $filesystem;

    public function __construct(KernelInterface $kernel, Filesystem $filesystem)
    {
        $this->projectDir = $kernel->getProjectDir();
        $this->filesystem = $filesystem;
    }

    public function download(Document $document): void
    {
        // Get the file path from your Document model
        $filePath = $this->resolveFilePath($document);
        
        if (!file_exists($filePath)) {
            throw new \RuntimeException("File not found: {$filePath}");
        }
        
        // Create a BinaryFileResponse
        $response = new BinaryFileResponse($filePath);
        
        // Set the content disposition
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $document->name ?? basename($filePath)
        );
        
        // Set appropriate headers
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'application/octet-stream');
        
        // Send the response and stop script execution
        $response->send();
        exit(); // Important to prevent additional output
    }
    
    public function deleteFile(Document $document): bool
    {
        try {
            $filePath = $this->resolveFilePath($document);
            
            // Check if file exists before attempting deletion
            if (!file_exists($filePath)) {
                return false;
            }
            
            // Use Symfony's Filesystem component for safer file operations
            $this->filesystem->remove($filePath);
            
            return !file_exists($filePath);
        } catch (\Exception $e) {
            // Log the exception
            error_log("Error deleting file: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Resolves the correct file path for a document
     * 
     * @param Document $document
     * @return string The resolved file path
     * @throws \RuntimeException If file cannot be found
     */
    private function resolveFilePath(Document $document): string
    {
        $relativePath = $document->uri;
        
        // Try different path combinations
        $possiblePaths = [
            $relativePath,
            "public{$relativePath}",
            "{$this->projectDir}/public{$relativePath}",
            "{$this->projectDir}{$relativePath}"
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        throw new \RuntimeException("File not found. Tried: " . implode(', ', $possiblePaths));
    }
}