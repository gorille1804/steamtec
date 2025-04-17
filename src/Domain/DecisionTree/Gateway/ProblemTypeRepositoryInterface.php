<?php

namespace Domain\DecisionTree\Gateway;

use Domain\DecisionTree\Data\Model\ProblemType;
use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;
use Domain\DecisionTree\Data\ObjectValue\CategoryId;

interface ProblemTypeRepositoryInterface
{
    /** @return ProblemType[] */
    public function findAllByCategory(CategoryId $categoryId): array;
    public function findById(ProblemTypeId $id): ?ProblemType;
    public function save(ProblemType $problemType): ProblemType;
    public function update(ProblemType $problemType): ProblemType;
    public function delete(ProblemType $problemType): void;
}