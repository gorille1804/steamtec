<?php

namespace Domain\MaintenanceNotification\Data\Model;

use Domain\MaintenanceNotification\Data\Enum\MaintenanceNotificationEnum;
use Domain\MaintenanceNotification\Data\ValueObject\MaintenanceNotificationId;
use Domain\ParcMachine\Data\Model\ParcMachine;

class MaintenanceNotification
{
    public function __construct(
        public readonly MaintenanceNotificationId $id,
        public MaintenanceNotificationEnum $type,
        public int $hours,
        public ParcMachine $machine,
        public \DateTimeInterface $createdAt
    ) {}
}