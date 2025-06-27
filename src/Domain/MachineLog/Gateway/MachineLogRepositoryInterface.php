<?php

namespace Domain\MachineLog\Gateway;

use Domain\MachineLog\Data\Model\MachineLog;
use Domain\ParcMachine\Data\ObjectValue\ParcMachineId;

interface MachineLogRepositoryInterface
{
    public function create(MachineLog $machineLog): MachineLog;
    public function update(MachineLog $machineLog): MachineLog;
    public function delete(MachineLog $machineLog): void;
    public function findByParcMachineId(ParcMachineId $parcMachineId): array;
}