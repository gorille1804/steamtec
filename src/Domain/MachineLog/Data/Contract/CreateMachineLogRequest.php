<?php

namespace Domain\MachineLog\Data\Contract;

use Domain\ParcMachine\Data\Model\ParcMachine;

class CreateMachineLogRequest
{
    /** @var array<array{parcMachine: ParcMachine, duration: int}> */
    public array $logs = [];
    public \DateTimeInterface $logDate;
}