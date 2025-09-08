<?php

namespace Domain\User\UseCase;

use Domain\User\Data\ObjectValue\UserId;

interface ToggleUserStatusUseCaseInterface
{
    public function __invoke(UserId $userId): void;
}
