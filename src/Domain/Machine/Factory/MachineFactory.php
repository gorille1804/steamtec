<?php

namespace Domain\Machine\Factory;

use Domain\Machine\Data\Contract\CreateMachineRequest;
use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Data\ObjectValue\MachineId;
use Domain\Machine\Data\Contract\UpdateMachineRequest;

class MachineFactory
{
    /**
     * Crée une nouvelle instance de Machine à partir de la requête de création.
     *
     * @param CreateMachineRequest $request
     * @return Machine
     */
    public static function make(CreateMachineRequest $request): Machine
    {
        // Créer une nouvelle machine avec un nouvel ID généré
        return new Machine(
            MachineId::make(), // ID généré
            $request->numeroIdentification,
            $request->nom,
            $request->marque,
            $request->tempUsage,
            $request->seuilMaintenance,
            $request->user,
            new \DateTimeImmutable(),
            null
        );
    }
    public static function update(Machine $machine, UpdateMachineRequest $request): Machine
    {
        $machine->numeroIdentification = $request->numeroIdentification;
        $machine->nom = $request->nom;
        $machine->marque = $request->marque;
        $machine->tempUsage = $request->tempUsage;
        $machine->seuilMaintenance = $request->seuilMaintenance;
        $machine->user = $request->user;
        $machine->updatedAt = new \DateTimeImmutable();
        return $machine;
    }

    public static function makeFromMachine(Machine $machine): UpdateMachineRequest
    {
      
        $formRequest = new UpdateMachineRequest();
        $formRequest->numeroIdentification = $machine->numeroIdentification;
        $formRequest->nom = $machine->nom;
        $formRequest->marque = $machine->marque;
        $formRequest->tempUsage = $machine->tempUsage;
        $formRequest->seuilMaintenance = $machine->seuilMaintenance;
        $formRequest->user=$machine->user;
        return $formRequest;	

    }	
   }
