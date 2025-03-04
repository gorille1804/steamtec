<?php

namespace Infrastructure\Database\FieldType;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Domain\Faq\Data\ObjectValue\FaqId;

class FaqIdType extends StringType
{
    public const NAME = "faqId";

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof FaqId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?FaqId
    {
        return $value ? new FaqId($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}