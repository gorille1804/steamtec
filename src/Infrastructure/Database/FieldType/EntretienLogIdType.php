<?php

namespace Infrastructure\Database\FieldType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\Entretien\Data\ObjectValue\EntretienLogId;

class EntretienLogIdType extends StringType
{
    public const NAME = "entretienLogId";

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof EntretienLogId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?EntretienLogId
    {
        return $value ? new EntretienLogId($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
} 