<?php

namespace Domain\Machine\UseCase;

use Domain\User\Data\Model\User;

Interface FindAllMachineByUserUseCaseInterface
{
    public function __invoke(User $user): ?array;
}