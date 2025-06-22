<?php

namespace Domain\Chantier\Data\Contract;

use Doctrine\Common\Collections\ArrayCollection;

class CreateChantierRequest
{
    public string $name;
    public string $description;
    public ArrayCollection $parcMachines;
    public string $machineSerialNumber;
    public \DateTimeInterface $chantierDate;
    public float $surface;
    public float $duration;
    public float $rendement;
    public array $surfaceTypes;
    public array $materials;
    public int $encrassementLevel;
    public int $vetusteLevel;
    public string $commentaire;
}