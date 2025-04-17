<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\CategoryId;
use Domain\DecisionTree\Data\Model\ProblemType;
use Domain\DecisionTree\Gateway\ProblemTypeRepositoryInterface;

class FindAllProblemTypesUseCase implements FindAllProblemTypesUseCaseInterface
{
    public function __construct(
        private readonly ProblemTypeRepositoryInterface $repository
    ) {}

    public function __invoke(CategoryId $categoryId): array
    {
        return $this->repository->findAllByCategory($categoryId);
    }
}