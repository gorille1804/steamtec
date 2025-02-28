<?php

namespace Infrastructure\Symfony\Services\File;

use Domain\Shared\Data\ObjectValue\FileInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SymfonyFileAdapter implements FileInterface
{
    private UploadedFile $file;
    private ?string $cachedContent = null;
    private ?string $mimeType = null;
    private ?string $originalFilename = null;
    private ?int $fileSize = null;

    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
        
        // Store these properties right away as they may not be available later
        $this->originalFilename = $file->getClientOriginalName();
        $this->mimeType = $file->getMimeType() ?? 'application/octet-stream';
        $this->fileSize = $file->getSize();
        
        // Cache the content immediately to avoid problems with temporary files
        try {
            if ($file->isReadable()) {
                $this->cachedContent = file_get_contents($file->getRealPath());
                if ($this->cachedContent === false) {
                    throw new \RuntimeException("Failed to read file content");
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't throw yet - we'll try again when getContent is called
            error_log("Warning: Could not prefetch file content: " . $e->getMessage());
        }
    }

    public function getContent(): string
    {
        // Return cached content if available
        if ($this->cachedContent !== null) {
            return $this->cachedContent;
        }
        
        try {
            // Try to read directly from the file using getRealPath() for the absolute path
            $path = $this->file->getRealPath();
            
            if (!$path || !file_exists($path)) {
                throw new \RuntimeException("Cannot find file at path: " . ($path ?: 'unknown'));
            }
            
            $content = file_get_contents($path);
            
            if ($content === false) {
                throw new \RuntimeException("Failed to read file content");
            }
            
            // Cache the content for future calls
            $this->cachedContent = $content;
            return $content;
            
        } catch (\Exception $e) {
            throw new \RuntimeException("Error reading file: " . $e->getMessage());
        }
    }

    public function getFilename(): string
    {
        return $this->originalFilename;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }
    
    public function getSize(): int
    {
        return $this->fileSize ?? 0;
    }
    
    public function getUnderlyingFile(): UploadedFile
    {
        return $this->file;
    }
}