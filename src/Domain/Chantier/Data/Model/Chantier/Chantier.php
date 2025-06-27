<?php

namespace Domain\Chantier\Data\Model\Chantier;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domain\Chantier\Data\ObjectValue\ChantierId;
use Domain\User\Data\Model\User;

class Chantier
{
    public Collection $chantierMachines;
    public Collection $machineLogs;

    public function __construct(
        public readonly ChantierId $id,
        public string $name,
        public ?string $description,
        public User $user,
        public \DateTimeInterface $createdAt,
        public ?\DateTimeInterface $updatedAt,
        public string $machineSerialNumber,
        public \DateTimeInterface $chantierDate,
        public string $surface,
        public string $duration,
        public string $rendement,
        public string $surfaceTypes,
        public array $materials,
        public int $encrassementLevel,
        public int $vetusteLevel,
        public ?string $commentaire
    ) {

        $this->chantierMachines = new ArrayCollection();
        $this->machineLogs = new ArrayCollection();
    }


}