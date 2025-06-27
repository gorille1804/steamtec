<?php

namespace Domain\Document\Data\ObjectValue;

use Domain\Shared\Data\ValueObject\Text;
use Ramsey\Uuid\Uuid;


class DocumentId extends Text implements \JsonSerializable
{
    private readonly string $uuid;


    /**
     * DocumentId constructor.
     */

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function make(): DocumentId
    {
        return new self(Uuid::uuid4());
    }

    public function getValue(): string
    {
        return $this->uuid;
    }

    public function jsonSerialize(): string
    {
        return $this->uuid;
    }
}