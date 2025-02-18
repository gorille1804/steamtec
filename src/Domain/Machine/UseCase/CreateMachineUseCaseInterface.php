<?php

namespace Domain\Machine\UseCase;

use Domain\Machine\Data\Contract\CreateMachineRequest;
use Domain\Machine\Data\Model\Machine;

interface CreateMachineUseCaseInterface
{
    public function __invoke(CreateMachineRequest $request): Machine;
}