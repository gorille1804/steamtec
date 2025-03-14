<?php

namespace Domain\Chantier\UseCase;

use Domain\User\Data\ObjectValue\UserId;

interface FindChantierByUserUseCaseInterface
{
    public function __invoke(int $page = 1, int $limit = 10, UserId $userId): array;
}