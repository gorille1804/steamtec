<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\DiagnosticStepId;
use Domain\DecisionTree\Data\Contract\UpdateDiagnosticStepRequest;
use Domain\DecisionTree\Data\Model\DiagnosticStep;
use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;
use Domain\DecisionTree\Gateway\DiagnosticStepRepositoryInterface;

class UpdateDiagnosticStepUseCase implements UpdateDiagnosticStepUseCaseInterface
{
    public function __construct(
        private readonly DiagnosticStepRepositoryInterface $repository
    ) {}

    public function __invoke(DiagnosticStepId $id, UpdateDiagnosticStepRequest $request): DiagnosticStep
    {
        $existing = $this->repository->findById($id);
        if ($existing === null) {
            throw new \RuntimeException('DiagnosticStep not found');
        }
        $updated = new DiagnosticStep(
            $id,
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
        return $this->repository->update($updated);
    }
}