<?php

namespace Domain\DecisionTree\Gateway;

use Domain\DecisionTree\Data\Model\Category;
use Domain\DecisionTree\Data\ObjectValue\CategoryId;

interface CategoryRepositoryInterface
{
    /** @return Category[] */
    public function findAll(): array;
    public function findById(CategoryId $id): ?Category;
    public function save(Category $category): Category;
    public function update(Category $category): Category;
    public function delete(Category $category): void;
}