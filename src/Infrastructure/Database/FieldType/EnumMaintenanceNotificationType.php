<?php

namespace Infrastructure\Database\FieldType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Domain\MaintenanceNotification\Data\Enum\MaintenanceNotificationEnum;

class EnumMaintenanceNotificationType extends Type
{
    const ENUM_MAINTENANCE_NOTIFICATION = 'enumMaintenanceNotification';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return " ENUM('maintenance_notification_regular', 'maintenance_notification_timely', 'maintenance_notification_emergency') ";
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof MaintenanceNotificationEnum ? $value->value : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value !== null ? MaintenanceNotificationEnum::tryFrom($value) : null;
    }

    public function getName()
    {
        return self::ENUM_MAINTENANCE_NOTIFICATION;
    }
}