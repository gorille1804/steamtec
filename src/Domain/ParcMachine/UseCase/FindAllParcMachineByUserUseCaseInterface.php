<?php

namespace Domain\ParcMachine\UseCase;

use Domain\User\Data\Model\User;
use Domain\User\Data\ObjectValue\UserId;

interface FindAllParcMachineByUserUseCaseInterface
{
    public function __invoke(User $user):array;
    public function getTotalCount(UserId $userId):int;
    public function getUsersParcRegistrationData(UserId $userId): array;
}