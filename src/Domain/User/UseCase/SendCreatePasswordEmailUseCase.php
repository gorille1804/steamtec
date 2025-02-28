<?php

namespace Domain\User\UseCase;

use Domain\User\Data\Model\User;
use Domain\User\Service\TokenServiceInterface;
use Domain\Shared\Service\Email\EmailServiceInterface;

class SendCreatePasswordEmailUseCase implements SendCreatePasswordEmailUseCaseInterface
{
    public function __construct(
        private readonly TokenServiceInterface $tokenService,
        private readonly EmailServiceInterface $emailService,
        private readonly string $appUrl,
        private readonly string $noReplyEmail
    ){}
    public function __invoke(User $user, string $templatePath): void
    {
       
        $token = $this->tokenService->generateToken($user->getId()->getValue(), 60);

        $resetLink = sprintf('%s/reset-password/%s', $this->appUrl, $token);
        $this->emailService->sendEmail(
            $templatePath,
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