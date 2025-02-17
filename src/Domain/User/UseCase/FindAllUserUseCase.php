<?php

namespace Domain\User\UseCase;

use Domain\User\Gateway\UserRepositoryInterface;

class FindAllUserUseCase implements FindAllUserUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $resposito
    ){}
    public function __invoke(): array
    {
        return $this->resposito->getAll();
    }
}