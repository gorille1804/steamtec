<?php

namespace Domain\Chantier\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Domain\Chantier\Data\Contract\UpdateChantierRequest;
use Domain\Chantier\Data\Model\Chantier\Chantier;


class UpdateChantierFactory
{
    public static function make(Chantier $chantier, UpdateChantierRequest $request):Chantier
    {
        // Calcul automatique du rendement
        $rendement = $request->duration > 0 ? $request->surface / $request->duration : 0;
        
        $chantier->name = $request->name;
        $chantier->description = $request->description;
        $chantier->machineSerialNumber = $request->machineSerialNumber;
        $chantier->chantierDate = $request->chantierDate;
        $chantier->surface = $request->surface;
        $chantier->duration = $request->duration;
        $chantier->rendement = $rendement;
        $chantier->surfaceTypes = $request->surfaceTypes;
        $chantier->materials = $request->materials;
        $chantier->encrassementLevel = $request->encrassementLevel;
        $chantier->vetusteLevel = $request->vetusteLevel;
        $chantier->commentaire = $request->commentaire;
        $chantier->updatedAt = new \DateTimeImmutable();

        return $chantier;
    }

    public static function makeRequest(Chantier $chantier, UpdateChantierRequest $request): UpdateChantierRequest
    {
        $parc = [];
        foreach ($chantier->chantierMachines as $chantierMachine) {  
            $parc[] = $chantierMachine->parcMachine;
        }

        $request->name = $chantier->name;
        $request->description = $chantier->description;
        $request->machineSerialNumber = $chantier->machineSerialNumber;
        $request->chantierDate = $chantier->chantierDate;
        $request->surface = $chantier->surface;
        $request->duration = $chantier->duration;
        $request->rendement = $chantier->rendement;
        $request->surfaceTypes = $chantier->surfaceTypes;
        $request->materials = $chantier->materials;
        $request->encrassementLevel = $chantier->encrassementLevel;
        $request->vetusteLevel = $chantier->vetusteLevel;
        $request->commentaire = $chantier->commentaire;
        $request->parcMachines = new ArrayCollection($parc);
        
        return $request;
    }
}