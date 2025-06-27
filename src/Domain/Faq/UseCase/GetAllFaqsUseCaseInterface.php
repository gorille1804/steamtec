<?php

namespace Domain\Faq\UseCase;

use Domain\Faq\Data\Model\Faq;

interface GetAllFaqsUseCaseInterface
{
    /**
     * Retrieve all FAQ entries
     *
     * @return array<Faq> Array of FAQ entities
     */
    public function __invoke(): array;
}