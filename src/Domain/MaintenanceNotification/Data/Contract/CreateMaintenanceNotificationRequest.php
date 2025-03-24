<?php

namespace Domain\MaintenanceNotification\Data\Contract;

use Domain\MaintenanceNotification\Data\Enum\MaintenanceNotificationEnum;
use Domain\ParcMachine\Data\Model\ParcMachine;

class CreateMaintenanceNotificationRequest
{
    public function __construct(
        public MaintenanceNotificationEnum $type,
        public ParcMachine $machine,
        public int $hours
    ) {
    }
}