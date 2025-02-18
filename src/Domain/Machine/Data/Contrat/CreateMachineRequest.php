<?php

namespace Domain\Machine\Data\Contract;

class CreateMachineRequest
{
    public string $numeroIdentification;
    public string $nom;
    public string $marque;
    public ?int $tempUsage=null;
    public int $seuilMaintenance;
    public ?string $userId=null;
}