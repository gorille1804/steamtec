<?php

namespace Domain\DecisionTree\Factory;

use Domain\DecisionTree\Data\Contract\CreateDiagnosticStepRequest;
use Domain\DecisionTree\Data\Model\DiagnosticStep;
use Domain\DecisionTree\Data\ObjectValue\DiagnosticStepId;
use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;

class DiagnosticStepFactory
{
    public static function make(CreateDiagnosticStepRequest $request): DiagnosticStep
    {
        return new DiagnosticStep(
            DiagnosticStepId::make(),
            $request->prompt,
            $request->needsTechnicalDoc,
            $request->parentStepId !== null ? new DiagnosticStepId($request->parentStepId) : null,
            $request->gotoStepId !== null ? new DiagnosticStepId($request->gotoStepId) : null,
            $request->isTerminal,
            new ProblemTypeId($request->problemTypeId)
        );
    }
}