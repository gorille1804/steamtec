<?php

namespace Domain\Machine\Factory;

use Domain\Machine\Data\Contract\CreateMachineRequest;
use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Data\ObjectValue\MachineId;
// use Domain\Machine\Data\Contract\UpdateMachineRequest;

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
            $request->userId,
            new \DateTimeImmutable(),
            null
        );
    }

   }
