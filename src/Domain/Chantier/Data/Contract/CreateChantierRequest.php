<?php

namespace Domain\Chantier\Data\Contract;

use Doctrine\Common\Collections\ArrayCollection;

class CreateChantierRequest
{
    public string $name;
    public string $description;
    public ArrayCollection $parcMachines;
}