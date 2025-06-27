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
            new ProblemTypeId($request->problemTypeId),
            $request->stepType,
            $request->parentStepId !== null ? new DiagnosticStepId($request->parentStepId) : null,
            $request->nextStepOKId !== null ? new DiagnosticStepId($request->nextStepOKId) : null,
            $request->nextStepKOId !== null ? new DiagnosticStepId($request->nextStepKOId) : null,
            $request->description,
            $request->needsTechnicalDoc,
            $request->stepOrder,
            $request->goTo,
            $request->page
        );
    }
}
