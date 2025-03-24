<?php

namespace Domain\MaintenanceNotification\Factory;
use Domain\MaintenanceNotification\Data\Contract\CreateMaintenanceNotificationRequest;
use Domain\MaintenanceNotification\Data\Enum\MaintenanceNotificationEnum;
use Domain\ParcMachine\Data\Model\ParcMachine;

class CreateMaitenanceNotificationRequestFactory
{
    public static function make(MaintenanceNotificationEnum $type,ParcMachine $machine, int $hours): CreateMaintenanceNotificationRequest 
    {
        return new CreateMaintenanceNotificationRequest(
            $type,
            $machine,
            $hours,
        );
    }
}