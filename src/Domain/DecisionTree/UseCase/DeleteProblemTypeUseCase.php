<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;
use Domain\DecisionTree\Gateway\ProblemTypeRepositoryInterface;

class DeleteProblemTypeUseCase implements DeleteProblemTypeUseCaseInterface
{
    public function __construct(
        private readonly ProblemTypeRepositoryInterface $repository
    ) {}

    public function __invoke(ProblemTypeId $id): void
    {
        $problemType = $this->repository->findById($id);
        if ($problemType === null) {
            throw new \RuntimeException('ProblemType not found');
        }
        $this->repository->delete($problemType);
    }
}