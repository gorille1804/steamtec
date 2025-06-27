<?php

namespace Infrastructure\Database\FieldType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\MachineLog\Data\ObjectValue\MachineLogId;

class MachineLogIdType extends StringType
{
    public const NAME = "machineLogId";

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof MachineLogId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?MachineLogId
    {
        return $value ? new MachineLogId($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}