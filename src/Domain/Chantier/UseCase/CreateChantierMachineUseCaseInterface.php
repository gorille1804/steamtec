<?php

namespace Domain\Chantier\UseCase;

use Domain\Chantier\Data\Contract\CreateChantierMachineRequest;
use Domain\Chantier\Data\Model\ChantierMachine\ChantierMachine;

interface CreateChantierMachineUseCaseInterface
{
    public function __invoke(CreateChantierMachineRequest $request): ChantierMachine;
}