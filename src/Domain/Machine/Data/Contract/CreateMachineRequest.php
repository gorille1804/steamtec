<?php

namespace Domain\Machine\Data\Contract;
use Domain\Shared\Data\ObjectValue\FileInterface;
class CreateMachineRequest
{
    public string $numeroIdentification;
    public string $nom;
    public string $marque;
    public int $seuilMaintenance;
    public ?FileInterface $ficheTechnique=null;
}