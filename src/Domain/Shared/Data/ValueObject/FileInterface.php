<?php

namespace Domain\Shared\Data\ObjectValue;

interface FileInterface
{
    public function getContent(): string;
    public function getFilename(): string;
    public function getMimeType(): string;
    public function getSize(): int;
}