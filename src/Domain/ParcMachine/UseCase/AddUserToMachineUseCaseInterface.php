<?php

namespace Domain\ParcMachine\UseCase;

use Domain\Machine\Data\Model\Machine;
use Domain\User\Data\Model\User;

interface AddUserToMachineUseCaseInterface
{
    /**
     * Ajoute un utilisateur à une machine
     *
     * @param Machine $machine
     * @param User $user
     * @return void
     */
    public function __invoke(Machine $machine, User $user): void;
} 