<?php

namespace Domain\MachineLog\UseCase;

use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\MachineLog\Data\Contract\CreateMachineLogRequest;
interface CreateMachineLogUseCaseInterface
{
    public function __invoke(CreateMachineLogRequest $request, Chantier $chantier): void;
}