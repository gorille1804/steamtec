<?php

namespace Domain\Machine\UseCase;

use Domain\Document\Data\Model\Document;
use Domain\Machine\Data\ObjectValue\MachineId;
use Domain\Machine\Data\Contract\UpdateMachineRequest;
use Domain\Machine\Data\Model\Machine;

interface UpdateMachineUseCaseInterface 
{

    public function __invoke(MachineId $id, UpdateMachineRequest $request, Document $document = null): Machine;
    
}