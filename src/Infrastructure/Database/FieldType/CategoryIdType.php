<?php

namespace Infrastructure\Database\FieldType;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Domain\DecisionTree\Data\ObjectValue\CategoryId;

class CategoryIdType extends StringType
{
    public const NAME = 'categoryId';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof CategoryId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CategoryId
    {
        return $value !== null ? new CategoryId($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}