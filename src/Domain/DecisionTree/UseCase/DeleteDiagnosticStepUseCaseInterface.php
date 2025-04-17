<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\DiagnosticStepId;

interface DeleteDiagnosticStepUseCaseInterface
{
    public function __invoke(DiagnosticStepId $id): void;
}