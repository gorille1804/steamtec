<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\User\Data\Model\User;
use Domain\User\Gateway\UserInterface;
use Domain\User\Gateway\UserRepositoryInterface;
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, User::class);
    }
    public function getAll(): array
    {
        return $this->findAll();
    }

    public function findByEmail(string $email): ?UserInterface
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findByid(int $id): ?UserInterface
    {
        return $this->findOneBy(['id' => $id]);   
    }
}