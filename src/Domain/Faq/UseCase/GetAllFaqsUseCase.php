<?php

namespace Domain\Faq\UseCase;

use Domain\Faq\Gateway\FaqRepositoryInterface;

class GetAllFaqsUseCase implements GetAllFaqsUseCaseInterface
{
    public function __construct(
        private readonly FaqRepositoryInterface $faqRepository
    ) {}
    /**
     * {@inheritdoc}
     */
    public function __invoke(): array
    {
        return $this->faqRepository->getAll();
    }
}