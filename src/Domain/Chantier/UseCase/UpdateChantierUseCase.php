<?php

namespace Domain\Chantier\UseCase;

use Domain\Chantier\Data\Contract\UpdateChantierRequest;
use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Chantier\Factory\CreateChantierMachineFactory;
use Domain\Chantier\Factory\UpdateChantierFactory;
use Domain\Chantier\Gateway\ChantierMachineRepositoryInterface;
use Domain\Chantier\Gateway\ChantierRepositoryInterface;

class UpdateChantierUseCase implements UpdateChantierUseCaseInterface
{
    public function __construct(
        private readonly ChantierRepositoryInterface $chantierRepository,
        private readonly ChantierMachineRepositoryInterface $chantierMachineRepository,
        private readonly CreateChantierMachineUseCaseInterface $createChantierMachineUseCase
    ){}

    public function __invoke(UpdateChantierRequest $request, Chantier $chantier): Chantier
    {
        $newMachineIds = array_map(function($machine) {
            return $machine->id; 
        }, $request->machines->toArray());
    
        $existingMachineIds = array_map(function($chantierMachine) {
            return $chantierMachine->machine->id;
        }, $chantier->chantierMachines->toArray());
    

        $machinesToRemove = array_filter(
            $chantier->chantierMachines->toArray(),
            function($chantierMachine) use ($newMachineIds) {
                return !in_array($chantierMachine->machine->id, $newMachineIds);
            }
        );

        $machinesToAdd = array_filter(
            $request->machines->toArray(),
            function($machine) use ($existingMachineIds) {
                return !in_array($machine->id, $existingMachineIds);
            }
        );

        foreach ($machinesToRemove as $machine) {
            $this->chantierMachineRepository->delete($machine);
        }

        foreach ($machinesToAdd as $machine) {
           $chantierMachine = CreateChantierMachineFactory::makeRequest($machine, $chantier);
           $this->createChantierMachineUseCase->__invoke($chantierMachine);
        }

        $newChantier = UpdateChantierFactory::make($chantier, $request);
        return $this->chantierRepository->update($newChantier);
    }
}