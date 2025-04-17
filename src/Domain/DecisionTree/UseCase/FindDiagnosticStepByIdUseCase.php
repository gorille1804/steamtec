<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\DiagnosticStepId;
use Domain\DecisionTree\Data\Model\DiagnosticStep;
use Domain\DecisionTree\Gateway\DiagnosticStepRepositoryInterface;

class FindDiagnosticStepByIdUseCase implements FindDiagnosticStepByIdUseCaseInterface
{
    public function __construct(
        private readonly DiagnosticStepRepositoryInterface $repository
    ) {}

    public function __invoke(DiagnosticStepId $id): ?DiagnosticStep
    {
        return $this->repository->findById($id);
    }
}