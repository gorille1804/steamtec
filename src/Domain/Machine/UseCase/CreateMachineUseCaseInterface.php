<?php

namespace Domain\Machine\UseCase;

use Domain\Document\Data\Model\Document;
use Domain\Machine\Data\Contract\CreateMachineRequest;
use Domain\Machine\Data\Model\Machine;

interface CreateMachineUseCaseInterface
{
    public function __invoke(CreateMachineRequest $request, Document $document): Machine;
}