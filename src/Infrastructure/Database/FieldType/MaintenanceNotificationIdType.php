<?php

namespace Infrastructure\Database\FieldType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\MaintenanceNotification\Data\ValueObject\MaintenanceNotificationId;

class MaintenanceNotificationIdType extends StringType
{
    public const NAME = "maintenanceNotificationId";

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof MaintenanceNotificationId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?MaintenanceNotificationId
    {
        return $value ? new MaintenanceNotificationId($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}