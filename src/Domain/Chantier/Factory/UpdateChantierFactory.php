<?php

namespace Domain\Chantier\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Domain\Chantier\Data\Contract\UpdateChantierRequest;
use Domain\Chantier\Data\Model\Chantier\Chantier;


class UpdateChantierFactory
{
    public static function make(Chantier $chantier, UpdateChantierRequest $request):Chantier
    {
        $chantier->name = $request->name;
        $chantier->description = $request->description;
        $chantier->hours = $request->hours;
        $chantier->updatedAt = new \DateTimeImmutable();

        return $chantier;

    }

    public static function makeRequest(Chantier $chantier, UpdateChantierRequest $request): UpdateChantierRequest
    {
        $machines = [];
        foreach ($chantier->chantierMachines as $chantierMachine) {      
            $machines[] = $chantierMachine->machine;
        }

        $request->name = $chantier->name;
        $request->description = $chantier->description;
        $request->hours = $chantier->hours;
        $request->machines = new ArrayCollection($machines);
        
        return $request;
    }
}