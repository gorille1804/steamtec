<?php

namespace Domain\ParcMachine\UseCase;

use Domain\Machine\Data\Model\Machine;
use Domain\User\Data\Model\User;

interface FindAllMachinesByUserUseCaseInterface
{
    /**
     * Récupère toutes les machines affectées à un utilisateur donné
     *
     * @param User $user
     * @return Machine[]
     */
    public function __invoke(User $user): array;
}
