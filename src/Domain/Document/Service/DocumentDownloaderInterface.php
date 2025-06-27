<?php

namespace Domain\Document\Service;

use Domain\Document\Data\Model\Document;

interface DocumentDownloaderInterface
{
    /**
     * Triggers a file download for the given document
     * 
     * @param Document $document
     * @return void
     */
    public function download(Document $document): void;
    
}