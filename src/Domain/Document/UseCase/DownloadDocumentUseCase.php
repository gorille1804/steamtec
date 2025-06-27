<?php

namespace Domain\Document\UseCase;

use Domain\Document\Data\Model\Document;
use Domain\Document\Service\DocumentDownloaderInterface;

class DownloadDocumentUseCase implements DownloadDocumentUseCaseInterface
{
    public function __construct(
        private readonly DocumentDownloaderInterface $serviceDownloader
    ){}
    public function __invoke(Document $document): void
    {
        $this->serviceDownloader->download($document);
    }
}
