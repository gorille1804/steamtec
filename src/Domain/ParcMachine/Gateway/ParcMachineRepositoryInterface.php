<?php
Namespace Domain\ParcMachine\Gateway;

use Domain\ParcMachine\Data\Model\ParcMachine;
use Domain\ParcMachine\Data\ObjectValue\ParcMachineId;
use Domain\User\Data\Model\User;

Interface ParcMachineRepositoryInterface
{
    public function save(ParcMachine $parcMachine):ParcMachine;
    public function findAllByUser(User $user):array;
    public function findById(ParcMachineId $id):?ParcMachine;
    public function delete(ParcMachine $parcMachine):void;
}