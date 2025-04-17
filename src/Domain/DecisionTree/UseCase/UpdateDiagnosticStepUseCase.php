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
            $request->prompt,
            $request->needsTechnicalDoc,
            $request->parentStepId !== null ? new DiagnosticStepId($request->parentStepId) : null,
            $request->gotoStepId !== null ? new DiagnosticStepId($request->gotoStepId) : null,
            $request->isTerminal,
            new ProblemTypeId($request->problemTypeId)
        );
        return $this->repository->update($updated);
    }
}