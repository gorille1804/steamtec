<?php

namespace Domain\DecisionTree\Data\Contract;

use Domain\DecisionTree\Data\Enum\DiagnosticStepType;

class UpdateDiagnosticStepRequest
{
    public string $problemTypeId;
    public DiagnosticStepType $stepType = DiagnosticStepType::SYMPTOM;
    public ?string $parentStepId = null;
    public ?string $nextStepOKId = null;
    public ?string $nextStepKOId = null;
    public string $description;
    public bool $needsTechnicalDoc = false;
    public int $stepOrder = 0;
    public ?int $goTo = null;
    public ?int $page = null;
}
