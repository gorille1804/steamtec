<?php

namespace Domain\Chantier\Factory;

use Domain\Chantier\Data\Contract\CreateChantierMachineRequest;
use Domain\Chantier\Data\Model\ChantierMachine\ChantierMachine;
use Domain\Chantier\Data\ObjectValue\ChantierMachineId;
use Domain\Machine\Data\Model\Machine;
use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\ParcMachine\Data\Model\ParcMachine;

class CreateChantierMachineFactory
{
    public static function make(CreateChantierMachineRequest $request): ChantierMachine
    {
       return new ChantierMachine(
           ChantierMachineId::make(),
           $request->chantier,
           $request->parcMachine,
       );
    }

    public static function makeRequest(ParcMachine $parcMachine, Chantier $chantier): CreateChantierMachineRequest
    {
        $request = new CreateChantierMachineRequest();
        $request->parcMachine = $parcMachine;
        $request->chantier = $chantier;

        return $request;
    }
}