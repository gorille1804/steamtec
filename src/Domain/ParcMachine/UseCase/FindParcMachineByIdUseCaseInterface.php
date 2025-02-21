<?php

namespace Domain\ParcMachine\UseCase;

use Domain\ParcMachine\Data\Model\ParcMachine;
use Domain\ParcMachine\Data\ObjectValue\ParcMachineId;

interface FindParcMachineByIdUseCaseInterface
{
    public function __invoke(ParcMachineId $parcMachineId):?ParcMachine;
}