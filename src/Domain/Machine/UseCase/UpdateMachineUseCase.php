<?php

namespace Domain\Machine\UseCase;

use Domain\Machine\Data\ObjectValue\MachineId;
use Domain\Machine\Data\Contract\UpdateMachineRequest;
use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Exception\MachineNotFonudException;
use Domain\Machine\Factory\MachineFactory;
use Domain\Machine\Gateway\MachineRepositoryInterface;

class UpdateMachineUseCase implements UpdateMachineUseCaseInterface
{
    public function __construct(
        private readonly MachineRepositoryInterface $repository
    ){}

    public function __invoke(MachineId $id, UpdateMachineRequest $request): Machine
    {
        $machine = $this->repository->findByid($id);
        if(!$machine){
            throw new MachineNotFonudException('Machine not found');
        }
        $machine = MachineFactory::update($machine, $request);

        return $this->repository->save($machine);
    }
}