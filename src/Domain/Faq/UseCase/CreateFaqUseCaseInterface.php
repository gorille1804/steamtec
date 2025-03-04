<?php

namespace Domain\Faq\UseCase;

use Domain\Faq\Data\Contract\CreateFaqRequest;
use Domain\Faq\Data\Model\Faq;

interface CreateFaqUseCaseInterface
{
    /**
     * Create a new FAQ entry
     *
     * @param CreateFaqRequest $request The FAQ creation request containing question and answer
     * @return Faq The newly created FAQ entity
     */
    public function __invoke(CreateFaqRequest $request): Faq;
}