<?php

namespace Domain\Machine\UseCase;

use Domain\Machine\Data\Contract\CreateMachineRequest;
use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Gateway\MachineRepositoryInterface;
use Domain\Machine\Factory\MachineFactory;

class CreateMachineUseCase implements CreateMachineUseCaseInterface
{
    public function __construct(
        private readonly MachineRepositoryInterface $repository,
    ){}

    public function __invoke(CreateMachineRequest $request): Machine
    {
        $machine = MachineFactory::make($request);	
        return $this->repository->save($machine);
    }   
}