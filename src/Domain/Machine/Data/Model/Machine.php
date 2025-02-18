<?php

namespace Domain\Machine\Data\Model;

use Domain\Machine\Data\ObjectValue\MachineId;
use Domain\User\Data\Model\User;

class Machine
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

}