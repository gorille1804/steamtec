<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;
use Domain\DecisionTree\Data\Model\DiagnosticStep;
use Domain\DecisionTree\Gateway\DiagnosticStepRepositoryInterface;

class FindAllDiagnosticStepsByProblemTypeUseCase implements FindAllDiagnosticStepsByProblemTypeUseCaseInterface
{
    public function __construct(
        private readonly DiagnosticStepRepositoryInterface $repository
    ) {}

    public function __invoke(ProblemTypeId $problemTypeId): array
    {
        return $this->repository->findAllByProblemType($problemTypeId);
    }
}