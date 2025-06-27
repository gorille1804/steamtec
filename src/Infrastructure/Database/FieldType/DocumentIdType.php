<?php

namespace Infrastructure\Database\FieldType;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Domain\Document\Data\ObjectValue\DocumentId;

class DocumentIdType extends StringType
{
    public const NAME = "documentId";

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof DocumentId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?DocumentId
    {
        return $value ? new DocumentId($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}