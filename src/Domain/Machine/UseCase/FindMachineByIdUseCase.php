<?php

namespace Domain\Machine\UseCase;

use Domain\Machine\Data\ObjectValue\MachineId;
use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Exception\MachineNotFoundException;
use Domain\Machine\Gateway\MachineRepositoryInterface;

class FindMachineByIdUseCase implements FindMachineByIdUseCaseInterface
{
    public function __construct(
        private readonly MachineRepositoryInterface $repository
    ){}

    public function __invoke(MachineId $id): ?Machine
    {
        $machine =  $this->repository->findByid($id);
        if(!$machine){
            throw new MachineNotFoundException('Machine not found');
        }

        return $machine;
    }
}