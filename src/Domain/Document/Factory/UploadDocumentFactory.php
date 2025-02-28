<?php

namespace Domain\Document\Factory;

use Domain\Document\Data\Contract\CreateDocumentRequest;
use Domain\Document\Data\Model\Document;
use Domain\Document\Data\ObjectValue\DocumentId;
use Domain\Document\Service\DocumentService;
use Domain\Shared\Data\ObjectValue\FileInterface;
use Domain\Shared\Service\File\FileStorageServiceInterface;

class UploadDocumentFactory
{
    /**
     * Crée un document à partir d'un fichier uploadé
     *
     * @param FileInterface $file Le fichier uploadé
     * @param FileStorageServiceInterface $fileStorageService Service de stockage de fichiers
     * @param DocumentService $documentService Service de gestion des documents
     * @param string $folder Dossier de destination (optionnel)
     * @return CreateDocumentRequest Requête pour créer un document
     */
    public static function createFromUploadedFile(
        FileInterface $file,
        FileStorageServiceInterface $fileStorageService,
        DocumentService $documentService,
        string $folder = 'documents'
    ): CreateDocumentRequest {
        // Stocker le fichier et obtenir l'URI
        $uri = $fileStorageService->store($file, $folder);
        
        // Créer une requête de création de document
        return new class($file->getFilename(), $uri, $documentService->getFormattedFileSize($file), $file->getMimeType()) extends CreateDocumentRequest {
            public function __construct(
                public string $name,
                public string $uri,
                public string $size,
                public string $type
            ) {}
        };
    }
    
    /**
     * Crée un document complet
     * 
     * @param CreateDocumentRequest $request
     * @return Document
     */
    public static function createDocument(CreateDocumentRequest $request): Document
    {
        return new Document(
            DocumentId::make(),
            $request->name,
            $request->uri,
            $request->size,
            $request->type,
            new \DateTime()
        );
    }
}