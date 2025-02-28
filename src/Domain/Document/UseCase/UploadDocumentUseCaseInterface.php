<?php

namespace Domain\Document\UseCase;
use Domain\Document\Data\Model\Document;
use Domain\Shared\Data\ObjectValue\FileInterface;

interface UploadDocumentUseCaseInterface
{
    public function __invoke(FileInterface $file, $folder = "machile"):Document;
}