<?php

namespace Domain\User\UseCase;

use Domain\User\Data\Contract\UpdateUserRequest;
use Domain\User\Data\Model\User;
use Domain\User\Data\ObjectValue\UserId;

interface UpdateUserUseCaseInterface
{
    public function __invoke(UserId $id, UpdateUserRequest $request): User;
}