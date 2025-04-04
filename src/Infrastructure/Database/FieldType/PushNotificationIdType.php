<?php

namespace Infrastructure\Database\FieldType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\PushNotification\Data\ValueObject\PushNotificationId;

class PushNotificationIdType extends StringType
{
    public const NAME = "pushNotificationId";

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof PushNotificationId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?PushNotificationId
    {
        return $value ? new PushNotificationId($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}