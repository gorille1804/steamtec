<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\Contract\CreateDiagnosticStepRequest;
use Domain\DecisionTree\Data\Model\DiagnosticStep;

interface CreateDiagnosticStepUseCaseInterface
{
    public function __invoke(CreateDiagnosticStepRequest $request): DiagnosticStep;
}