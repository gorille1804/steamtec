<?php

namespace Domain\ParcMachine\UseCase;

use Domain\ParcMachine\Data\Model\ParcMachine;

interface DeleteParcMachineUseCaseInterface
{
    public function __invoke(ParcMachine $parcMachine):void;
}