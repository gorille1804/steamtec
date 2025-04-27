<?php

namespace Domain\DecisionTree\Data\Model;

use Domain\DecisionTree\Data\ObjectValue\CategoryId;

class Category
{
    public function __construct(
        public CategoryId $id,
        public string $name,
        public int $position = 0
    ) {}
}
