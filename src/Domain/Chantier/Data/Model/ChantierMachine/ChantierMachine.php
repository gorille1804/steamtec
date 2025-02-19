<?php

namespace Domain\Chantier\Data\Model\ChantierMachine;
use Domain\Chantier\Data\ObjectValue\ChantierMachineId;
use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Machine\Data\Model\Machine;
class ChantierMachine
{
    public function __construct(
        public readonly ChantierMachineId $id,
        public Chantier $chantier,
        public Machine $machine
    ) {
    }
}