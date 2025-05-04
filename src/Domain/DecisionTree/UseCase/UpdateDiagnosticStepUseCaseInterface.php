<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\DiagnosticStepId;
use Domain\DecisionTree\Data\Contract\UpdateDiagnosticStepRequest;
use Domain\DecisionTree\Data\Model\DiagnosticStep;

interface UpdateDiagnosticStepUseCaseInterface
{
    public function __invoke(DiagnosticStepId $id, UpdateDiagnosticStepRequest $request): DiagnosticStep;
}