<?php

namespace Domain\DecisionTree\Data\Model;

use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;
use Domain\DecisionTree\Data\ObjectValue\CategoryId;

class ProblemType
{
    public function __construct(
        public ProblemTypeId $id,
        public string $name,
        public CategoryId $categoryId
    ) {}
}