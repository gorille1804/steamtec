<?php

namespace Infrastructure\Database\FieldType;

use Doctrine\DBAL\Types\StringType;
use Domain\Chantier\Data\ObjectValue\ChantierMachineId;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class ChantierMachineIdType extends StringType
{
    public const NAME = "chantierMachineId";


    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof ChantierMachineId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ChantierMachineId
    {
        return $value ? new ChantierMachineId($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}