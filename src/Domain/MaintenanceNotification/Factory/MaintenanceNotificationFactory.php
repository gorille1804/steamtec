<?php

namespace Domain\MaintenanceNotification\Factory;

use Domain\MaintenanceNotification\Data\Contract\CreateMaintenanceNotificationRequest;
use Domain\MaintenanceNotification\Data\Model\MaintenanceNotification;
use Domain\MaintenanceNotification\Data\ValueObject\MaintenanceNotificationId;

class MaintenanceNotificationFactory
{
    public static function make(CreateMaintenanceNotificationRequest $request): MaintenanceNotification
    {
        return new MaintenanceNotification(
            MaintenanceNotificationId::make(),
            $request->type,
            $request->hours,
            $request->machine,
            new \DateTime(),
        );
    }
}