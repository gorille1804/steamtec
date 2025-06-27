<?php

namespace Domain\Document\Service;

use Domain\Document\Data\Model\Document;

interface DocumentDeleteInterface
{
    /**
     * Deletes the file associated with the given document
     * 
     * @param Document $document
     * @return bool True if deletion was successful, false otherwise
     */
    public function deleteFile(Document $document): bool;
}