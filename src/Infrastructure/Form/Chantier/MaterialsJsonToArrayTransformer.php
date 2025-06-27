<?php

namespace Infrastructure\Form\Chantier;

use Symfony\Component\Form\DataTransformerInterface;

class MaterialsJsonToArrayTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): mixed
    {
        // Transforme l'array PHP en JSON pour le champ caché
        return json_encode($value ?? []);
    }

    public function reverseTransform(mixed $value): mixed
    {
        // Transforme le JSON du champ caché en array PHP
        if (is_array($value)) {
            return $value;
        }
        return json_decode($value ?: '[]', true) ?: [];
    }
} 