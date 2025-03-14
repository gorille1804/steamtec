<?php

namespace Domain\Chantier\Gateway;

use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Chantier\Data\ObjectValue\ChantierId;
use Domain\User\Data\ObjectValue\UserId;

interface ChantierRepositoryInterface
{
    public function getAll(): array;
    public function getTotalChantiers(): int;
    public function findById(ChantierId $id): ?Chantier;
    public function findByUser(int $page = 1, int $limit = 10, UserId $userId): array;
    public function findByCriteria(array $criteria): array;
    public function save(Chantier $chantier): Chantier;
    public function update(Chantier $chantier): Chantier;
    public function delete(Chantier $chantier): void;
}