<?php

namespace Domain\User\UseCase;

use Domain\User\Data\Model\User;

interface SendCreatePasswordEmailUseCaseInterface
{
    public function __invoke(User $user, string $templatePath): void;
}