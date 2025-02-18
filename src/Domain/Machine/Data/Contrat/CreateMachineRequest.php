<?php

namespace Domain\Machine\Data\Contract;

use Domain\User\Data\ObjectValue\UserId;

class CreateMachineRequest
{
    public string $numeroIdentification;
    public string $nom;
    public string $marque;
    public ?int $tempUsage=null;
    public int $seuilMaintenance;
    public ?UserId $userId=null;
}