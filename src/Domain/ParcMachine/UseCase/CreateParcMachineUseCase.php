<?php
Namespace Domain\ParcMachine\UseCase;

use Domain\ParcMachine\Data\Contract\CreateParcMachineRequest;
use Domain\ParcMachine\Data\Model\ParcMachine;
use Domain\ParcMachine\Factory\ParcMachineFactory;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;

class CreateParcMachineUseCase implements CreateParcMachineUseCaseInterface
{
    public function __construct(
        private readonly ParcMachineRepositoryInterface $repository,
    )
    {}
    public function __invoke(CreateParcMachineRequest $request): ParcMachine
    {
        $parcMachine=ParcMachineFactory::make($request);

        return $this->repository->save($parcMachine);;
    }
}