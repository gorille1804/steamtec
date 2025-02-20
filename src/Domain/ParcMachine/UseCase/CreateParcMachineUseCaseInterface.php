<?php

namespace Domain\ParcMachine\UseCase;

use Domain\ParcMachine\Data\Contract\CreateParcMachineRequest;
use Domain\ParcMachine\Data\Model\ParcMachine;

interface CreateParcMachineUseCaseInterface
{
    public function __invoke(CreateParcMachineRequest $request): ParcMachine;
}