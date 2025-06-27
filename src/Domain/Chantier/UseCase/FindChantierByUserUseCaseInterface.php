<?php

namespace Domain\Chantier\UseCase;

use Domain\User\Data\ObjectValue\UserId;

interface FindChantierByUserUseCaseInterface
{
    public function __invoke(UserId $userId, int $page = 1, int $limit = 10): array;
}