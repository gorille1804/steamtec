<?php

namespace Domain\Faq\UseCase;

use Domain\Faq\Gateway\FaqRepositoryInterface;

class FindAllFaqsUseCase implements FindAllFaqsUseCaseInterface
{
    public function __construct(
        private readonly FaqRepositoryInterface $faqRepository
    ) {}

    /**
     * {@inheritdoc}
     */
    public function __invoke(int $page = 1, int $limit = 10): array
    {
        return $this->faqRepository->getAll($page, $limit);
    }

    public function count(): int
    {
        return $this->faqRepository->getTotalFaqs();
    }
}