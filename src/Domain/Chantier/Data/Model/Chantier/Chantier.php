<?php

namespace Domain\Chantier\Data\Model\Chantier;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domain\Chantier\Data\ObjectValue\ChantierId;
use Domain\User\Data\Model\User;

class Chantier
{
    public Collection $chantierMachines;

    public function __construct(
        public readonly ChantierId $id,
        public string $name,
        public string $description,
        public User $user,
        public \DateTimeInterface $createdAt,
        public ?\DateTimeInterface $updatedAt
    ) {

        $this->chantierMachines = new ArrayCollection();
    }


}