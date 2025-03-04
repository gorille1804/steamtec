<?php

namespace Domain\Faq\UseCase;

use Domain\Faq\Data\Contract\UpdateFaqRequest;
use Domain\Faq\Data\Model\Faq;
use Domain\Faq\Data\ObjectValue\FaqId;
use Domain\Faq\Factory\FaqFactory;
use Domain\Faq\Gateway\FaqRepositoryInterface;
use Domain\Faq\Exception\FaqNotFoundException;

class UpdateFaqUseCase implements UpdateFaqUseCaseInterface
{
    public function __construct(
        private readonly FaqRepositoryInterface $repository
    ){}

    public function __invoke(FaqId $id, UpdateFaqRequest $request): Faq
    {
        $faq = $this->repository->findById($id);
        if(!$faq){
            throw new FaqNotFoundException("FAQ not found");
        }
        $faq = FaqFactory::update($faq, $request);
        return $this->repository->update($faq);
    }

    public function updateStatus(Faq $faq): Faq
    {
        $faq->isActive = !$faq->isActive;
        return $this->repository->update($faq);
    }
}