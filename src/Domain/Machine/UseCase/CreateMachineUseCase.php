<?php

namespace Domain\Machine\UseCase;

use Domain\Document\Data\Model\Document;
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

    public function __invoke(CreateMachineRequest $request, Document $document = null): Machine
    {
        $machine = MachineFactory::make($request, $document);	
        return $this->repository->save($machine);
    }   
}