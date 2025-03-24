<?php
namespace Domain\MachineLog\UseCase;

use Domain\ParcMachine\Data\Model\ParcMachine;

interface MaintenanceCheckerUseCaseInterface
{
    public function _invoke(ParcMachine $parcMachine): bool;
}
