<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;
use Domain\DecisionTree\Data\Contract\UpdateProblemTypeRequest;
use Domain\DecisionTree\Data\Model\ProblemType;
use Domain\DecisionTree\Data\ObjectValue\CategoryId;
use Domain\DecisionTree\Gateway\ProblemTypeRepositoryInterface;

class UpdateProblemTypeUseCase implements UpdateProblemTypeUseCaseInterface
{
    public function __construct(
        private readonly ProblemTypeRepositoryInterface $repository
    ) {}

    public function __invoke(ProblemTypeId $id, UpdateProblemTypeRequest $request): ProblemType
    {
        $existing = $this->repository->findById($id);
        if ($existing === null) {
            throw new \RuntimeException('ProblemType not found');
        }
        $updated = new ProblemType($id, $request->name, new CategoryId($request->categoryId));
        return $this->repository->update($updated);
    }
}