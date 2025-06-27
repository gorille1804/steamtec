<?php

namespace Infrastructure\Database\FieldType;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;

class ProblemTypeIdType extends StringType
{
    public const NAME = 'problemTypeId';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof ProblemTypeId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ProblemTypeId
    {
        return $value !== null ? new ProblemTypeId($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}