<?php

namespace Domain\Document\Data\Contract;

class CreateDocumentRequest
{
    public string $name;
    public string $size;
    public string $type;
    public string $uri;
}