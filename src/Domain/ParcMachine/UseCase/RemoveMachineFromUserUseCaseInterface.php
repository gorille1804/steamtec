<?php

namespace Domain\ParcMachine\UseCase;

use Domain\Machine\Data\Model\Machine;
use Domain\User\Data\Model\User;

interface RemoveMachineFromUserUseCaseInterface
{
    /**
     * Supprime une machine d'un utilisateur
     *
     * @param User $user
     * @param Machine $machine
     * @return void
     */
    public function __invoke(User $user, Machine $machine): void;
}
