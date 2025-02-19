<?php

namespace Infrastructure\Database\FieldType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\Chantier\Data\ObjectValue\ChantierId;

class ChantierIdType extends StringType
{
    public const NAME = "chantierId";

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof ChantierId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ChantierId
    {
        return $value ? new ChantierId($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}