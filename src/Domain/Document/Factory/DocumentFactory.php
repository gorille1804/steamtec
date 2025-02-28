<?php

namespace Domain\Document\Factory;

use Domain\Document\Data\Contract\CreateDocumentRequest;
use Domain\Document\Data\Model\Document;
use Domain\Document\Data\ObjectValue\DocumentId;

class DocumentFactory
{
    public static function make(CreateDocumentRequest $request): Document
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