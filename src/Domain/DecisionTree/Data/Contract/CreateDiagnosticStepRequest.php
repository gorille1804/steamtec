<?php

namespace Domain\DecisionTree\Data\Contract;

class CreateDiagnosticStepRequest
{
    public string $prompt;
    public bool $needsTechnicalDoc = false;
    public ?string $parentStepId = null;
    public ?string $gotoStepId = null;
    public bool $isTerminal = false;
    public string $problemTypeId;
}