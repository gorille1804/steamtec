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
    public function findByUser(UserId $userId, int $page = 1, int $limit = 10): array;
    public function findByUserWithSearch(UserId $userId, string $search = '', int $page = 1, int $limit = 10): array;
    public function getTotalByUserWithSearch(UserId $userId, string $search = ''): int;
    public function findByCriteria(array $criteria): array;
    public function save(Chantier $chantier): Chantier;
    public function update(Chantier $chantier): Chantier;
    public function delete(Chantier $chantier): void;
}