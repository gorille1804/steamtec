<?php

namespace Domain\ParcMachine\UseCase;

use Domain\User\Data\Model\User;

interface FindAllParcMachineByUserUseCaseInterface
{
    public function __invoke(User $user):array;
}