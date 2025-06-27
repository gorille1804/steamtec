<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\DiagnosticStepId;
use Domain\DecisionTree\Data\Model\DiagnosticStep;

interface FindDiagnosticStepByIdUseCaseInterface
{
    public function __invoke(DiagnosticStepId $id): ?DiagnosticStep;
}