<?php

namespace Infrastructure\Symfony\Security;

use Domain\User\Exception\UserNotFonudException;
use Domain\User\Gateway\UserRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function __construct(
        private readonly UserRepositoryInterface  $respository
    ){}

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
       $user =  $this->respository->findByEmail($identifier);
       if(!$user){
            throw new UserNotFonudException("User not found");
       }

       return new SymfonyUserAdapter($user);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof SymfonyUserAdapter) {
            throw new \LogicException('Invalid user type');
        }

        $freshUser = $this->respository->findById($user->getUser()->getId());
        return new SymfonyUserAdapter($freshUser);
    }

    public function supportsClass(string $class): bool
    {
        return SymfonyUserAdapter::class === $class;
    }
}