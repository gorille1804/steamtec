<?php

namespace Domain\Machine\Gateway;

use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Data\ObjectValue\MachineId;
use Domain\Machine\Gateway\MachineInterface;
use Domain\User\Data\Model\User;

interface MachineRepositoryInterface
{
    public function findById(MachineId $id): ?MachineInterface;
    public function save(Machine $machine): Machine;
    public function getAll(int $page, int $limit, ?string $search = null):array;
    public function findAll(): array;
    public function delete(Machine $machine): void;
    public function findAllByUser(User $user):array;
    public function getTotalMachines(?string $search = null): int;
    public function getAllMachinesRegistrationData(): array;
}