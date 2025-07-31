<?php

namespace Domain\ParcMachine\UseCase;

use Domain\Machine\Data\Model\Machine;
use Domain\User\Data\Model\User;

interface FindAllUsersByMachineUseCaseInterface
{
    /**
     * Récupère tous les utilisateurs affectés à une machine donnée
     *
     * @param Machine $machine
     * @return User[]
     */
    public function __invoke(Machine $machine): array;
} 