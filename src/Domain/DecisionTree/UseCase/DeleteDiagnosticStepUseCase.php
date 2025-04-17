<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\DiagnosticStepId;
use Domain\DecisionTree\Gateway\DiagnosticStepRepositoryInterface;

class DeleteDiagnosticStepUseCase implements DeleteDiagnosticStepUseCaseInterface
{
    public function __construct(
        private readonly DiagnosticStepRepositoryInterface $repository
    ) {}

    public function __invoke(DiagnosticStepId $id): void
    {
        $step = $this->repository->findById($id);
        if ($step === null) {
            throw new \RuntimeException('DiagnosticStep not found');
        }
        $this->repository->delete($step);
    }
}