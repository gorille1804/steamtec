<?php

namespace Domain\DecisionTree\Data\Model;

use Domain\DecisionTree\Data\Enum\DiagnosticStepType;
use Domain\DecisionTree\Data\ObjectValue\DiagnosticStepId;
use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;

class DiagnosticStep
{
    public function __construct(
        public DiagnosticStepId $id,
        public ProblemTypeId $problemTypeId,
        public DiagnosticStepType $stepType,
        public ?DiagnosticStepId $parentStepId,
        public ?DiagnosticStepId $nextStepOKId,
        public ?DiagnosticStepId $nextStepKOId,
        public string $description,
        public bool $needsTechnicalDoc,
        public int $stepOrder,
        public ?int $goTo,
        public ?int $page
    ) {}
}
