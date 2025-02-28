<?php

namespace Domain\Machine\Data\Model;

use Domain\Document\Data\Model\Document;
use Domain\Machine\Data\ObjectValue\MachineId;
use Domain\Machine\Gateway\MachineInterface;

class Machine implements MachineInterface
{
    public function __construct(
        public MachineId $id,
        public string $numeroIdentification,
        public string $nom,
        public string $marque,
        public int $seuilMaintenance,
        public Document $ficheTechnique,
        public \DateTimeInterface $createdAt,
        public ?\DateTimeInterface $updatedAt = null,
    ) {}
    public function getId(): MachineId
    {
        return $this->id;
    }

    public function getNumeroIdentification(): string
    {
        return $this->numeroIdentification;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getMarque(): string
    {
        return $this->marque;
    }

    public function getSeuilMaintenance(): int
    {
        return $this->seuilMaintenance;
    }

    public function getFicheTechnique(): ?Document
    {
        return $this->ficheTechnique;
    }

}