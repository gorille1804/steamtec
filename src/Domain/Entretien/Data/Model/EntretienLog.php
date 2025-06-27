<?php

namespace Domain\Entretien\Data\Model;

use Domain\Entretien\Data\ObjectValue\EntretienLogId;
use Domain\ParcMachine\Data\Model\ParcMachine;

class EntretienLog
{
    public function __construct(
        public readonly EntretienLogId $id,
        public ParcMachine $parcMachine,
        public \DateTimeInterface $logDate,
        public int $volumeHoraire,
        public string $activite,
        public \DateTimeInterface $createdAt,
        public ?\DateTimeInterface $updatedAt = null,
    ) {}

    public function getId(): EntretienLogId
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->parcMachine->getUser();
    }

    public function getMachine()
    {
        return $this->parcMachine->getMachine();
    }

    public function getParcMachine(): ParcMachine
    {
        return $this->parcMachine;
    }

    public function getLogDate(): \DateTimeInterface
    {
        return $this->logDate;
    }

    public function getVolumeHoraire(): int
    {
        return $this->volumeHoraire;
    }

    public function getActivite(): string
    {
        return $this->activite;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
} 