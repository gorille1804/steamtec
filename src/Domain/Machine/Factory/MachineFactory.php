<?php

namespace Domain\Machine\Factory;

use Domain\Document\Data\Model\Document;
use Domain\Machine\Data\Contract\CreateMachineRequest;
use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Data\ObjectValue\MachineId;
use Domain\Machine\Data\Contract\UpdateMachineRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MachineFactory
{
    /**
     * Crée une nouvelle instance de Machine à partir de la requête de création.
     *
     * @param CreateMachineRequest $request
     * @return Machine
     */
    public static function make(CreateMachineRequest $request, Document $document = null): Machine
    {
        return new Machine(
            MachineId::make(), // ID généré
            $request->numeroIdentification,
            $request->nom,
            $request->marque,
            $request->seuilMaintenance,
            $document,
            new \DateTimeImmutable(),
            null
        );
    }

     /**
     * Met à jour une machine existante avec de nouvelles données.
     *
     * @param Machine $machine
     * @param UpdateMachineRequest $request
     * @return Machine
     */
    public static function update(Machine $machine, UpdateMachineRequest $request, Document $document): Machine
    {
        $machine->numeroIdentification = $request->numeroIdentification;
        $machine->nom = $request->nom;
        $machine->marque = $request->marque;
        $machine->seuilMaintenance = $request->seuilMaintenance;
        $machine->ficheTechnique = $document;
         $machine->updatedAt = new \DateTimeImmutable();
        return $machine;
    }

     /**
     * Crée une instance d'UpdateMachineRequest à partir de la machine existante.
     *
     * @param Machine $machine
     * @return UpdateMachineRequest
     */
    public static function makeFromMachine(Machine $machine): UpdateMachineRequest
    {
        $formRequest = new UpdateMachineRequest();
        $formRequest->numeroIdentification = $machine->numeroIdentification;
        $formRequest->nom = $machine->nom;
        $formRequest->marque = $machine->marque;
        $formRequest->seuilMaintenance = $machine->seuilMaintenance;
        $formRequest->ficheTechnique = null;
        return $formRequest;	
    }

}