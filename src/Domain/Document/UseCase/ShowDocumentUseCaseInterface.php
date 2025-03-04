<?php

namespace Domain\Document\UseCase;

use Domain\Document\Data\Model\Document;

interface ShowDocumentUseCaseInterface
{
    /**
     * Show document details
     * 
     * @param Document $document
     * @return Document
     */
    public function __invoke(Document $document): void;
}