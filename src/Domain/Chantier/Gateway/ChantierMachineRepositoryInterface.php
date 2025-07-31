<?php

namespace Domain\Chantier\Gateway;

use Domain\Chantier\Data\Model\ChantierMachine\ChantierMachine;
use Domain\Chantier\Data\ObjectValue\ChantierMachineId;
use Domain\ParcMachine\Data\Model\ParcMachine;

interface ChantierMachineRepositoryInterface
{
    public function findById(ChantierMachineId $id): ?array;
    public function findByCriteria(array $criteria): array;
    public function save(ChantierMachine $chantierMachine):ChantierMachine;
    public function delete(ChantierMachine $chantierMachine): void;
    public function findAllByParcMachine(ParcMachine $parcMachine): array;
    public function deleteByParcMachine(ParcMachine $parcMachine): void;
}