<?php

namespace Domain\Machine\UseCase;

use Domain\Machine\Data\Contract\CreateMachineRequest;
use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Gateway\MachineRepositoryInterface;
use Domain\Machine\Factory\MachineFactory;
use Domain\User\Gateway\UserRepositoryInterface;

class CreateMachineUseCase implements CreateMachineUseCaseInterface
{
    public function __construct(
        private readonly MachineRepositoryInterface $repository,
        private readonly UserRepositoryInterface $userRepository
    ){}

    public function __invoke(CreateMachineRequest $request): Machine
    {
        dd($request);
        // $machine = MachineFactory::make($request);	
        return $this->repository->save($machine);
    }   
}