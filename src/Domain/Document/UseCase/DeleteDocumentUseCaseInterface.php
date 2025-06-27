<?php

namespace Domain\Document\UseCase;

use Domain\Document\Data\Model\Document;

interface DeleteDocumentUseCaseInterface
{
    public function __invoke(Document $document): void;
}
