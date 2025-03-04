<?php

namespace Domain\Faq\UseCase;

use Domain\Faq\Data\Contract\DeleteFaqRequest;
use Domain\Faq\Data\Model\Faq;
use Domain\Faq\Gateway\FaqRepositoryInterface;

class DeleteFaqUseCase implements DeleteFaqUseCaseInterface
{
    public function __construct(
        private readonly FaqRepositoryInterface $repository
    ){}

    public function __invoke(Faq $faq): void
    {
        $this->repository->delete($faq);
    }
}