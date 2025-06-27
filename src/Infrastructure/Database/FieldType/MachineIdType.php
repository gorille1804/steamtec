<?php

namespace Infrastructure\Database\FieldType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\Machine\Data\ObjectValue\MachineId;

class MachineIdType extends StringType
{
    public const NAME = "machineId";

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof MachineId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?MachineId
     {
         return $value ? new MachineId($value) : null;
     }

     public function getName(): string
     {
         return self::NAME;
     }


}