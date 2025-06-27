<?php

namespace Domain\MachineLog\UseCase;

use Domain\ParcMachine\Data\Model\ParcMachine;

interface SendMaintenanceMailUseCaseInteface
{
    public function __invoke(ParcMachine $parcMachine, ?string $content): void;
}