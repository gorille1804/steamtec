<?php

namespace Domain\Chantier\Data\Contract;

use Doctrine\Common\Collections\ArrayCollection;

class CreateChantierRequest
{
    public string $name;
    public ?string $description;
    public ArrayCollection $parcMachines;
    public string $machineSerialNumber;
    public \DateTimeInterface $chantierDate;
    public string $surface;
    public string $duration;
    public string $rendement;
    public string $surfaceTypes;
    public array $materials;
    public int $encrassementLevel;
    public int $vetusteLevel;
    public ?string $commentaire;

    public function __construct()
    {
        $this->name = '';
        $this->description = null;
        $this->parcMachines = new ArrayCollection();
        $this->machineSerialNumber = '';
        $this->chantierDate = new \DateTime();
        $this->surface = '0.00';
        $this->duration = '0.0';
        $this->rendement = '0.00';
        $this->surfaceTypes = 'TOIT';
        $this->materials = [];
        $this->encrassementLevel = 1;
        $this->vetusteLevel = 1;
        $this->commentaire = null;
    }
}