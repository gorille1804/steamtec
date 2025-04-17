<?php

namespace Domain\DecisionTree\Data\Model;

use Domain\DecisionTree\Data\ObjectValue\DiagnosticStepId;
use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;

class DiagnosticStep
{
    public function __construct(
        public DiagnosticStepId $id,
        public string $prompt,
        public bool $needsTechnicalDoc,
        public ?DiagnosticStepId $parentStepId,
        public ?DiagnosticStepId $gotoStepId,
        public bool $isTerminal,
        public ProblemTypeId $problemTypeId
    ) {}
}