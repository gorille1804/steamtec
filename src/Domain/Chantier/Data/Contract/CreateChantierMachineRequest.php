<?php

namespace Domain\Chantier\Data\Contract;

use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\ParcMachine\Data\Model\ParcMachine;

class CreateChantierMachineRequest
{
    public Chantier $chantier;
    public ParcMachine $parcMachine;

}