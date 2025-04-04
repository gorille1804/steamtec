<?php
namespace Domain\MaintenanceNotification\Service;

use Domain\MaintenanceNotification\Data\Constant\MaintenanceNotification;
use Domain\ParcMachine\Data\Model\ParcMachine;

class MaintenanceCheckerService
{
      public function maintenanceChecker(ParcMachine $parcMachine): bool
    {
        $hoursSinceCreation = (new \DateTime())->diff($parcMachine->getCreatedAt())->h 
        + (new \DateTime())->diff($parcMachine->getCreatedAt())->days * 24;

        foreach (MaintenanceNotification::TIMELY_MAINTENANCE_RANGES as $range) {
            if ($hoursSinceCreation >= $range['start'] && $hoursSinceCreation < $range['end']) {
                return true;
            }
        }
        return false;
    }
}
