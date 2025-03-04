<?php

namespace Domain\ParcMachine\Factory;

use Domain\ParcMachine\Data\Contract\CreateParcMachineRequest;
use Domain\ParcMachine\Data\Model\ParcMachine;
use Domain\ParcMachine\Data\ObjectValue\ParcMachineId;
use Domain\User\Data\Model\User;

class ParcMachineFactory
{
    /**
     * Crée une nouvelle instance de ParcMachine à partir de la requête de création.
     *
     * @param CreateParcMachineRequest $request
     * @return ParcMachine
     */
    public static function make(CreateParcMachineRequest $request): ParcMachine
    {
        // Créer une nouvelle ParcMachine avec un nouvel ID généré
        return new ParcMachine(
            ParcMachineId::make(), // ID généré
            $request->machine,
            $request->user,
            $request->tempUsage,
            0,
            new \DateTimeImmutable(),
            null
        );
    }

    public static function makeRequest(User $user): CreateParcMachineRequest
    {
        $request = new CreateParcMachineRequest();
        $request->user = $user;
        $request->tempUsage = 0;

        return $request;
    }

    public static function updateDuration(ParcMachine $parcMachine, int $duration): ParcMachine
    {
        $parcMachine->tempUsage = $parcMachine->tempUsage + $duration;
        $parcMachine->currentHourUse = self::calculateCurentHourUse($parcMachine, $duration);
        $parcMachine->updatedAt = new \DateTimeImmutable();
        return $parcMachine;
    }

    private static function calculateCurentHourUse(ParcMachine $parcMachine, int $duration): int
    {
        $machine = $parcMachine->machine;
        $maxUse = $machine->seuilMaintenance;

        $currentHourUse = $parcMachine->currentHourUse + $duration;
        if( $currentHourUse > $maxUse ){
            $currentHourUse = $currentHourUse - $maxUse;
        }

        return $currentHourUse;
    }

}