<?php

namespace Domain\Faq\UseCase;

use Domain\Faq\Data\Model\Faq;

interface FindAllFaqsUseCaseInterface
{
    /**
     * Retrieve all FAQ entries
     *
     * @return array<Faq> Array of FAQ entities
     */
    public function __invoke(int $page =  1, int $limit = 10): array;
    public function count(): int;
}