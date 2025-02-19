<?php

namespace Domain\Chantier\Data\Contract;

use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Machine\Data\Model\Machine;

class CreateChantierMachineRequest
{
    public Chantier $chantier;
    public Machine $machine;

}