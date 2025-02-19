<?php

namespace Domain\Chantier\Gateway;

use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Chantier\Data\ObjectValue\ChantierId;

interface ChantierRepositoryInterface
{
    public function getAll(): array;
    public function findById(ChantierId $id): ?Chantier;
    public function findByCriteria(array $criteria): array;
    public function save(Chantier $chantier): Chantier;
    public function update(Chantier $chantier): Chantier;
    public function delete(Chantier $chantier): void;
}