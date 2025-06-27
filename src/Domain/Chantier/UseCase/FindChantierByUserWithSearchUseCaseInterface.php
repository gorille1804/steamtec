<?php

namespace Domain\Chantier\UseCase;

use Domain\User\Data\ObjectValue\UserId;

interface FindChantierByUserWithSearchUseCaseInterface
{
    public function __invoke(UserId $userId, string $search = '', int $page = 1, int $limit = 10): array;
    public function getTotal(UserId $userId, string $search = ''): int;
} 