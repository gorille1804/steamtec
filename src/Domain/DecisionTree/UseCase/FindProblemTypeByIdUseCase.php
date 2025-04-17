<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;
use Domain\DecisionTree\Data\Model\ProblemType;
use Domain\DecisionTree\Gateway\ProblemTypeRepositoryInterface;

class FindProblemTypeByIdUseCase implements FindProblemTypeByIdUseCaseInterface
{
    public function __construct(
        private readonly ProblemTypeRepositoryInterface $repository
    ) {}

    public function __invoke(ProblemTypeId $id): ?ProblemType
    {
        return $this->repository->findById($id);
    }
}