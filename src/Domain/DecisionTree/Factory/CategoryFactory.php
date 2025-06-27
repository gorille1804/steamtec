<?php

namespace Domain\DecisionTree\Factory;

use Domain\DecisionTree\Data\Contract\CreateCategoryRequest;
use Domain\DecisionTree\Data\Model\Category;
use Domain\DecisionTree\Data\ObjectValue\CategoryId;

class CategoryFactory
{
    public static function make(CreateCategoryRequest $request): Category
    {
        return new Category(
            CategoryId::make(),
            $request->name
        );
    }
}