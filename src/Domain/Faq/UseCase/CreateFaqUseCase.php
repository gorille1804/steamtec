<?php

namespace Domain\Faq\UseCase;

use Domain\Faq\Data\Contract\CreateFaqRequest;
use Domain\Faq\Data\Model\Faq;
use Domain\Faq\Gateway\FaqRepositoryInterface;
use Domain\Faq\Factory\FaqFactory;

class CreateFaqUseCase implements CreateFaqUseCaseInterface
{
    public function __construct(
        private readonly FaqRepositoryInterface $repository
    ){}

    public function __invoke(CreateFaqRequest $request): Faq
    {
        $faq = FaqFactory::make($request);
        return $this->repository->save($faq);
    }
}