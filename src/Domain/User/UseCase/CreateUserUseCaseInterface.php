<?php

namespace Domain\User\UseCase;

use Domain\User\Data\Contract\CreateUserRequest;
use Domain\User\Data\Model\User;

interface CreateUserUseCaseInterface
{
    public function __invoke(CreateUserRequest $request): User;
}