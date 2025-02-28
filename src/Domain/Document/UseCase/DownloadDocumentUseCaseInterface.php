<?php

namespace Domain\Document\UseCase;

use Domain\Document\Data\Model\Document;

interface DownloadDocumentUseCaseInterface
{
    public function __invoke(Document $document): void;
}