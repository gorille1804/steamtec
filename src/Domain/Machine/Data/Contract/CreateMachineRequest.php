<?php

namespace Domain\Machine\Data\Contract;

use Domain\User\Data\Model\User;

class CreateMachineRequest
{
    public string $numeroIdentification;
    public string $nom;
    public string $marque;
    public ?int $tempUsage = null;
    public int $seuilMaintenance;
    public ?User $user = null;
}