<?php

namespace Domain\Faq\UseCase;

use Domain\Faq\Data\Contract\UpdateFaqRequest;
use Domain\Faq\Data\Model\Faq;
use Domain\Faq\Data\ObjectValue\FaqId;

interface UpdateFaqUseCaseInterface
{
    /**
     * Update an existing FAQ entry
     *
     * @param UpdateFaqRequest $request The FAQ update request containing updated data
     * @return Faq The updated FAQ entity
     */
    public function __invoke(FaqId $id,UpdateFaqRequest $request): Faq;
    public function updateStatus(Faq $faq): Faq;

}