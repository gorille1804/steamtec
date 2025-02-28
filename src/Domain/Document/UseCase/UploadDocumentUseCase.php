<?php

namespace Domain\Document\UseCase;

use Domain\Document\Data\Model\Document;
use Domain\Document\Factory\DocumentFactory;
use Domain\Document\Factory\UploadDocumentFactory;
use Domain\Document\Gateway\DocumentRepositoryInterface;
use Domain\Document\Service\DocumentService;
use Domain\Shared\Data\ObjectValue\FileInterface;
use Domain\Shared\Service\File\FileStorageServiceInterface;

class UploadDocumentUseCase implements UploadDocumentUseCaseInterface
{
    public function __construct(
        private readonly DocumentRepositoryInterface $repository,
        private readonly FileStorageServiceInterface $fileStorageService,
        private readonly DocumentService $documentService
    ){}

    public function __invoke(FileInterface $file, $folder = "machine"): Document
    {
        $createDocumenet = UploadDocumentFactory::createFromUploadedFile($file, $this->fileStorageService, $this->documentService, $folder);
        $document = DocumentFactory::make($createDocumenet);

        return $this->repository->create($document);
    }
}