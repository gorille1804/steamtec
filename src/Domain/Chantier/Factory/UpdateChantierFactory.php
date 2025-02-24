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
        $chantier->updatedAt = new \DateTimeImmutable();

        return $chantier;

    }

    public static function makeRequest(Chantier $chantier, UpdateChantierRequest $request): UpdateChantierRequest
    {
        $parc = [];
        foreach ($chantier->chantierMachines as $chantierMachine) {  
            $parc[] =$chantierMachine->parcMachine;
        }

        $request->name = $chantier->name;
        $request->description = $chantier->description;
        $request->parcMachines = new ArrayCollection($parc);
        
        return $request;
    }
}