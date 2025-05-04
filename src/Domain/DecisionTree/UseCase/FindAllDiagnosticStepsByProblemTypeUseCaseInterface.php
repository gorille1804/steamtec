<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;
use Domain\DecisionTree\Data\Model\DiagnosticStep;

interface FindAllDiagnosticStepsByProblemTypeUseCaseInterface
{
    /**
     * @param ProblemTypeId $problemTypeId
     * @return DiagnosticStep[]
     */
    public function __invoke(ProblemTypeId $problemTypeId): array;
}