<?php

namespace Domain\Machine\UseCase;

use Domain\Machine\Data\Model\Machine;

interface GetAllMachinesUseCaseInterface
{
    /**
     * Récupère toutes les machines sans pagination
     *
     * @return Machine[]
     */
    public function __invoke(): array;
}
