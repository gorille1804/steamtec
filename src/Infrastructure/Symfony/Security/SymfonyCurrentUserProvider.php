<?php

namespace Infrastructure\Symfony\Security;

use Domain\Shared\TokenStorageInterface\CurrentUserProviderInterface;
use Domain\User\Data\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SymfonyCurrentUserProvider implements CurrentUserProviderInterface
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage
    ) {}

    public function getCurrentUser(): User
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            throw new \LogicException('Aucun utilisateur connecté.');
        }

        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            throw new \LogicException('Utilisateur invalide.');
        }

        // Ici, on suppose que tu utilises un adaptateur pour récupérer l’entité du domaine
        return $user->getUser(); // retourne ton entité `Domain\User\Entity\User`
    }
}
