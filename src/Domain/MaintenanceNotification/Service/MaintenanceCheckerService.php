<?php
namespace Domain\MaintenanceNotification\Service;

use Domain\MaintenanceNotification\Data\Constant\MaintenanceNotification;
use Domain\ParcMachine\Data\Model\ParcMachine;

class MaintenanceCheckerService
{
      public function maintenanceChecker(ParcMachine $parcMachine): bool
    {
        $tempUsage = $parcMachine->getTempUsage();
        $yearsSinceCreation = $parcMachine->getCreatedAt()->diff(new \DateTime())->y;

        foreach (MaintenanceNotification::MAINTENANCE_THRESHOLDS as $hourThreshold => $yearThreshold) {
            if ($tempUsage >= $hourThreshold || $yearsSinceCreation >= $yearThreshold) {
                return true;
            }
        }
        return false;
    }
}
