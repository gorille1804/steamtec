<?php

namespace Domain\DecisionTree\Gateway;

use Domain\DecisionTree\Data\Model\DiagnosticStep;
use Domain\DecisionTree\Data\ObjectValue\DiagnosticStepId;
use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;

interface DiagnosticStepRepositoryInterface
{
    /** @return DiagnosticStep[] */
    public function findAllByProblemType(ProblemTypeId $problemTypeId): array;
    /** @return DiagnosticStep[] */
    public function findAllBySymptome(string $symptome): array;
    public function findById(DiagnosticStepId $id): ?DiagnosticStep;
    public function save(DiagnosticStep $diagnosticStep): DiagnosticStep;
    public function update(DiagnosticStep $diagnosticStep): DiagnosticStep;
    public function delete(DiagnosticStep $diagnosticStep): void;
}
