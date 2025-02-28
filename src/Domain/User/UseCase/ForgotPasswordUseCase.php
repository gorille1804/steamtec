<?php

namespace Domain\User\UseCase;

use Domain\User\UseCase\ForgotPasswordUseCaseInterface;
use Domain\User\Gateway\UserRepositoryInterface;
use Domain\User\Data\Contract\ForgotPasswordRequest;
use Domain\User\Exception\UserNotFonudException;
use Domain\User\UseCase\SendCreatePasswordEmailUseCaseInterface;

class ForgotPasswordUseCase implements ForgotPasswordUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly SendCreatePasswordEmailUseCaseInterface $sendCreatePasswordEmailUseCase
    ){}

    public function __invoke(ForgotPasswordRequest $request): void
    {
            $user = $this->repository->findByEmail($request->email);
            if(!$user) {
                throw new UserNotFonudException('Utilisateur non trouvé');
            }
            $this->sendCreatePasswordEmailUseCase->__invoke($user,'email/security/reset_password.html.twig');
    }
}