<?php

namespace Domain\Document\UseCase;

use Domain\Document\Data\Model\Document;
use Domain\Document\Gateway\DocumentRepositoryInterface;
use Domain\Document\Service\DocumentDeleteInterface;

class DeleteDocumentUseCase implements DeleteDocumentUseCaseInterface
{
    public function __construct(
        private readonly DocumentRepositoryInterface $repository,
        private readonly DocumentDeleteInterface $documentInterface
    ){}
    public function __invoke(Document $document): void
    {
        try {
          $this->documentInterface->deleteFile($document);
          $this->repository->delete($document);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}