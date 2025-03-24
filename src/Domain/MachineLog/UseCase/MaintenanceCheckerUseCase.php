<?php
namespace Domain\MachineLog\UseCase;

use Domain\ParcMachine\Data\Model\ParcMachine;

class MaintenanceCheckerUseCase implements MaintenanceCheckerUseCaseInterface
{
    private const MAINTENANCE_THRESHOLDS = [
        700 => 1, 1400 => 2, 2100 => 3, 2800 => 4, 3500 => 5
    ];

    public function _invoke(ParcMachine $parcMachine): bool
    {
        $tempUsage = $parcMachine->getTempUsage();
        $yearsSinceCreation = $parcMachine->getCreatedAt()->diff(new \DateTime())->y;

        foreach (self::MAINTENANCE_THRESHOLDS as $hourThreshold => $yearThreshold) {
            if ($tempUsage >= $hourThreshold || $yearsSinceCreation >= $yearThreshold) {
                return true;
            }
        }
        return false;
    }
}
