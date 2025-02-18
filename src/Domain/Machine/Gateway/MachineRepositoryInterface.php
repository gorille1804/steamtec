<?php

namespace Domain\Machine\Gateway;

use Domain\Machine\Data\Model\Machine;

interface MachineRepositoryInterface
{
    // public function findById(MachineId $id): ?MachineInterface;
    public function save(Machine $machine): Machine;
    public function getAll():array;
    // public function findByNumeroIdentification(string $numeroIdentification): ?MachineInterface;
}