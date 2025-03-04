<?php

namespace Domain\Document\UseCase;

use Domain\Document\Data\Model\Document;
use Domain\Document\UseCase\ShowDocumentUseCaseInterface;

class ShowDocumentUseCase implements ShowDocumentUseCaseInterface
{
    public function __construct(
        private readonly string $publicDirectory,
    ) {}

    public function __invoke(Document $document): void
    {
        $basePath = realpath($this->publicDirectory);
        if ($basePath === false) {
            header("HTTP/1.0 500 Internal Server Error");
            exit;
        }

        $relativePath = ltrim($document->uri, '/\\');
        $filePath = realpath($basePath . DIRECTORY_SEPARATOR . $relativePath);

        if ($filePath === false || strpos($filePath, $basePath) !== 0 || !is_file($filePath)) {
            header("HTTP/1.0 404 Not Found");
            exit;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');

        readfile($filePath);
        exit;
    }
}