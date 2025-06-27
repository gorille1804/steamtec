<?php
namespace Infrastructure\Symfony\Security;

use Domain\User\Gateway\AuthenticationManagerInterface;
use Domain\User\Gateway\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Bundle\SecurityBundle\Security;

class SymfonyAuthenticationManager implements AuthenticationManagerInterface
{
    public function __construct(
        private readonly UserAuthenticatorInterface $userAuthenticator,
        private readonly FormLoginAuthenticator $authenticator,
        private readonly Security $security
    ) {}

    public function authenticate(UserInterface $user, mixed $context = null): mixed
    {
        if (!$context instanceof Request) {
            throw new \InvalidArgumentException('Context must be an instance of Symfony Request');
        }

        $securityUser = new SecurityUser($user);
        return $this->userAuthenticator->authenticateUser(
            $securityUser,
            $this->authenticator,
            $context
        );
    }

    public function logout(mixed $context = null): void
    {
        $this->security->logout(false);
    }
}