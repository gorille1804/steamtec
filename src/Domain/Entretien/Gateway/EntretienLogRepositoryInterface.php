<?php

namespace Domain\Entretien\Gateway;

use Domain\Entretien\Data\Model\EntretienLog;
use Domain\Entretien\Data\ObjectValue\EntretienLogId;
use Domain\Machine\Data\Model\Machine;
use Domain\User\Data\Model\User;
use Domain\ParcMachine\Data\Model\ParcMachine;

interface EntretienLogRepositoryInterface
{
    public function save(EntretienLog $entretienLog): EntretienLog;
    public function findById(EntretienLogId $id): ?EntretienLog;
    public function findAllByUser(User $user): array;
    public function findAllByMachine(Machine $machine): array;
    public function findAllByUserAndMachine(User $user, Machine $machine): array;
    public function findAllByParcMachine(ParcMachine $parcMachine): array;
    public function delete(EntretienLog $entretienLog): void;
    public function update(EntretienLog $entretienLog): EntretienLog;
} 