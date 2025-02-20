<?php

namespace Domain\ParcMachine\Data\Contract;

use Domain\Machine\Data\Model\Machine;
use Domain\User\Data\Model\User;

class CreateParcMachineRequest
{
    public ?Machine $machine = null;
    public ?User $user = null;
    public ?int $tempUsage = null;
}