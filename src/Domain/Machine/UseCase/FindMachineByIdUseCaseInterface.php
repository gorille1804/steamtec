<?php

namespace Domain\Machine\UseCase;

use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Data\ObjectValue\MachineId;

Interface FindMachineByIdUseCaseInterface
{
    public function __invoke(MachineId $id): ?Machine;
}