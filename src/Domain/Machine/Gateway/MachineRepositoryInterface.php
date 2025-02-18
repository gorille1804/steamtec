<?php

namespace Domain\Machine\Gateway;

use Domain\Machine\Data\Model\Machine;

interface MachineRepositoryInterface
{
    // public function getAll(): array;
    // public function findById(MachineId $id): ?MachineInterface;
    public function save(Machine $machine): Machine;
    // public function findByNumeroIdentification(string $numeroIdentification): ?MachineInterface;
}