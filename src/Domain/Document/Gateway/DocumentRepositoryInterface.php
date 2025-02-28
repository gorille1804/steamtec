<?php

namespace Domain\Document\Gateway;

use Domain\Document\Data\Model\Document;

interface DocumentRepositoryInterface
{
    public function create(Document $document): Document;
    public function delete(Document $document): void;
}