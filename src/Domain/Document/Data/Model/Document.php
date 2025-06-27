<?php

namespace Domain\Document\Data\Model;
use Domain\Document\Data\ObjectValue\DocumentId;

class Document
{
    public function __construct(
        public DocumentId $id,
        public string $name,
        public string $uri,
        public string $size,
        public string $type,
        public \DateTime $createdAt
    ) {}
}