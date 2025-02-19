<?php

namespace Domain\Chantier\Factory;

use Domain\Chantier\Data\Contract\CreateChantierMachineRequest;
use Domain\Chantier\Data\Model\ChantierMachine\ChantierMachine;
use Domain\Chantier\Data\ObjectValue\ChantierMachineId;
use Domain\Machine\Data\Model\Machine;
use Domain\Chantier\Data\Model\Chantier\Chantier;
class CreateChantierMachineFactory
{
    public static function make(CreateChantierMachineRequest $request): ChantierMachine
    {
       return new ChantierMachine(
           ChantierMachineId::make(),
           $request->chantier,
           $request->machine,
       );
    }

    public static function makeRequest(Machine $machine, Chantier $chantier): CreateChantierMachineRequest
    {
        $request = new CreateChantierMachineRequest();
        $request->machine = $machine;
        $request->chantier = $chantier;

        return $request;
    }
}