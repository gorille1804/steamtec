<?php

namespace Domain\User\UseCase;
use Domain\User\Data\ObjectValue\UserId;

interface DeleteUserUseCaseInterface
{
    public function __invoke(UserId $userId): void;
}