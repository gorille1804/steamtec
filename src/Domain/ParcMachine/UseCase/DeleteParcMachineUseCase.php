<?php
Namespace Domain\ParcMachine\UseCase;

use Domain\ParcMachine\Data\Model\ParcMachine;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;

class DeleteParcMachineUseCase implements DeleteParcMachineUseCaseInterface
{
    public function __construct(
        private readonly ParcMachineRepositoryInterface $repository,
    )
    {}
    public function __invoke(ParcMachine $parcMachine): void
    {
        $this->repository->delete($parcMachine);
    }
}