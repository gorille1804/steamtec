<?php
Namespace Domain\ParcMachine\Gateway;

use Domain\ParcMachine\Data\Model\ParcMachine;
use Domain\ParcMachine\Data\ObjectValue\ParcMachineId;
use Domain\User\Data\Model\User;
use Domain\User\Data\ObjectValue\UserId;

Interface ParcMachineRepositoryInterface
{
    public function save(ParcMachine $parcMachine):ParcMachine;
    public function getAll(): array;
    public function findAllByUser(User $user):array;
    public function findById(ParcMachineId $id):?ParcMachine;
    public function delete(ParcMachine $parcMachine):void;
    public function update(ParcMachine $parcMachine):ParcMachine;
    public function getTotalCount(UserId $userId):int;
    public function getUsersParcRegistrationData(UserId $userId): array;
    public function findMachinesReachingMaintenanceThreshold(): array;
}