<?php

namespace Domain\MachineLog\Data\Model;

use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\MachineLog\Data\ObjectValue\MachineLogId;
use Domain\ParcMachine\Data\Model\ParcMachine;

class MachineLog
{
    public function __construct(
        public readonly MachineLogId $machineLogId,
        public ParcMachine $parcMachine,
        public Chantier $chantier,
        public int $duration,
        public \DateTimeInterface $logDate,
        public \DateTimeInterface $createdAt,
        public ?\DateTimeInterface $updatedAt,
    ) {
    }
}