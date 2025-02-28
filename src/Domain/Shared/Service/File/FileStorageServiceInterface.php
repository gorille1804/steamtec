<?php

namespace Domain\Shared\Service\File;

use Domain\Shared\Data\ObjectValue\FileInterface;

interface FileStorageServiceInterface
{
    public function store(FileInterface $file, string $folder): string;
    public function retrieve(string $path): ?FileInterface;
    public function delete(string $path): void;
}