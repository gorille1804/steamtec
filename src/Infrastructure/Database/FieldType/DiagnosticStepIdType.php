<?php

namespace Infrastructure\Database\FieldType;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Domain\DecisionTree\Data\ObjectValue\DiagnosticStepId;

class DiagnosticStepIdType extends StringType
{
    public const NAME = 'diagnosticStepId';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof DiagnosticStepId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?DiagnosticStepId
    {
        return $value !== null ? new DiagnosticStepId($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}