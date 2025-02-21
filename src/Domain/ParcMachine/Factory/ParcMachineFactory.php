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

}