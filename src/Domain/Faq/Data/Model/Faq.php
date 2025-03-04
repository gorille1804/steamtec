<?php

namespace Domain\Faq\Data\Model;
use Domain\Faq\Data\ObjectValue\FaqId;

class Faq
{
    public function __construct(
        public readonly FaqId $id,
        public string $question,
        public string $answer,
        public bool $isActive = true,
    ){}
}