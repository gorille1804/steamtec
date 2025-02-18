<?php

namespace Domain\User\UseCase;

use Domain\User\Data\Model\User;
use Domain\User\Data\ObjectValue\UserId;

interface FindUserByIdUseCaseInterface
{
    public function __invoke(UserId $id): ?User;
}