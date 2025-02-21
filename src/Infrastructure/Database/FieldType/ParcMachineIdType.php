<?php

namespace Infrastructure\Database\FieldType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\ParcMachine\Data\ObjectValue\ParcMachineId;

class ParcMachineIdType extends StringType
{
    public const NAME = "parcMachineId";

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof ParcMachineId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ParcMachineId
     {
         return $value ? new ParcMachineId($value) : null;
     }

     public function getName(): string
     {
         return self::NAME;
     }


}