<?php
Namespace Domain\ParcMachine\UseCase;

use Domain\ParcMachine\Data\Model\ParcMachine;
use Domain\ParcMachine\Data\ObjectValue\ParcMachineId;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;

class FindParcMachineByIdUseCase implements FindParcMachineByIdUseCaseInterface
{
    public function __construct(
        private readonly ParcMachineRepositoryInterface $repository,
    )
    {}
    public function __invoke(ParcMachineId $parcMachineId): ?ParcMachine
    {
        $parcMachines= $this->repository->findById($parcMachineId);
        return $parcMachines;
    }
}