<?php

namespace Domain\MachineLog\UseCase;

use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\MachineLog\Factory\MachineLogFactory;
use Domain\MachineLog\Gateway\MachineLogRepositoryInterface;
use Domain\MachineLog\UseCase\CreateMachineLogUseCaseInterface;
use Domain\MachineLog\Data\Contract\CreateMachineLogRequest;
use Domain\ParcMachine\Factory\ParcMachineFactory;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;

class CreateMachineLogUseCase implements CreateMachineLogUseCaseInterface
{
    public function __construct(
        private readonly MachineLogRepositoryInterface $repository,
        private readonly ParcMachineRepositoryInterface $parcMachineRepository
    ){}

    public function __invoke(CreateMachineLogRequest $request, Chantier $chantier): void
    {
        $machileLogs = MachineLogFactory::make($request, $chantier);
        if(count($machileLogs)>0){
            foreach ($machileLogs as $log) {
                $log =   $this->repository->create($log);
    
                //update ParcMachine aggregate hours
                $parcMachine =  ParcMachineFactory::updateDuration($log->parcMachine, $log->duration);
                $this->parcMachineRepository->update($parcMachine);
            }
        }


    }
}