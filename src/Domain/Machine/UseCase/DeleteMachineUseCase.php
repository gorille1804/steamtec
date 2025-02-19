<?php
namespace Domain\Machine\UseCase;

use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Data\ObjectValue\MachineId;
use Domain\Machine\Exception\MachineNotFoundException;
use Domain\Machine\Gateway\MachineRepositoryInterface;


class DeleteMachineUseCase implements DeleteMachineUseCaseInterface
{
    public function __construct(
        private readonly MachineRepositoryInterface $repository
    ){}

    public function __invoke(MachineId $machineId): void
    {
        $machine = $this->repository->findByid($machineId);
        if(!$machine){
            throw new MachineNotFoundException('Machine not found');
        }
        $this->repository->delete($machine);
    }
}