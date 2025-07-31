<?php

namespace Domain\User\UseCase;

use Domain\User\Data\Model\User;

interface FindAllUsersUseCaseInterface
{
    /**
     * Récupère tous les utilisateurs
     *
     * @return User[]
     */
    public function __invoke(): array;
} 