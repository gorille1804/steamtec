<?php

namespace Domain\Faq\UseCase;

use Domain\Faq\Data\Contract\DeleteFaqRequest;
use Domain\Faq\Data\Model\Faq;

interface DeleteFaqUseCaseInterface
{
    /**
     * Delete an existing FAQ entry
     *
     * @param DeleteFaqRequest $request The FAQ deletion request containing the FAQ ID
     * @return void
     */
    public function __invoke(Faq $faq): void;
}