<?php

namespace Infrastructure\Database\FieldType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\User\Data\ObjectValue\UserId;

class UserIdType extends StringType
{
    public const NAME = "userId";

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof UserId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UserId
     {
         return $value ? new UserId($value) : null;
     }

     public function getName(): string
     {
         return self::NAME;
     }


}