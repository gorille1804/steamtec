<?php

namespace Domain\Machine\Data\Model;

use Domain\Machine\Data\ObjectValue\MachineId;
use Domain\Machine\Gateway\UserInterface;
use Domain\User\Data\Model\User;

class Machine implements UserInterface
{
    public function __construct(
        public MachineId $id,
        public string $numeroIdentification,
        public string $nom,
        public string $marque,
        public ?int $tempUsage,
        public int $seuilMaintenance,
        public ?User $user = null,
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

    public function getTempUsage(): ?int
    {
        return $this->tempUsage;
    }

    public function getSeuilMaintenance(): int
    {
        return $this->seuilMaintenance;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

}