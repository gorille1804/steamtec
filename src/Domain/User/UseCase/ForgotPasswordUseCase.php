<?php

namespace Domain\User\UseCase;

use Domain\Shared\Service\Email\EmailServiceInterface;
use Domain\User\UseCase\ForgotPasswordUseCaseInterface;
use Domain\User\Gateway\UserRepositoryInterface;
use Domain\User\Data\Contract\ForgotPasswordRequest;
use Domain\User\Exception\UserNotFonudException;
use Domain\User\Service\TokenServiceInterface;

class ForgotPasswordUseCase implements ForgotPasswordUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly TokenServiceInterface $tokenService,
        private readonly EmailServiceInterface $emailService,
        private readonly string $appUrl,
        private readonly string $noReplyEmail	
    ){}

    public function __invoke(ForgotPasswordRequest $request): void
    {
        
            $user = $this->repository->findByEmail($request->email);
            if(!$user) {
                throw new UserNotFonudException('Utilisateur non trouvé');
            }

            $token = $this->tokenService->generateToken($user->getId()->getValue(), 60);

            $resetLink = sprintf('%s/reset-password/%s', $this->appUrl, $token);
            $this->emailService->sendEmail(
                'email/reset_password.html.twig',
                [
                    'user' => $user,
                    'resetLink' => $resetLink
                ],
                'Reset password',
                $this->noReplyEmail,
                [$user->getEmail()]
            );
    }
}