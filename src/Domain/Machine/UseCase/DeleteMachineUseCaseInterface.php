<?php

namespace Domain\Machine\UseCase;
use Domain\Machine\Data\ObjectValue\MachineId;

interface DeleteMachineUseCaseInterface
{
    public function __invoke(MachineId $machineId): void;
}