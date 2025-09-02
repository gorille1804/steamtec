<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\User\Data\Model\User;
use Domain\User\Data\ObjectValue\UserId;
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
    public function getAll(int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        
        return $this->createQueryBuilder('u')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getAllUsers(): array
    {
        return $this->createQueryBuilder('u')
            ->getQuery()
            ->getResult();
    }

    public function getTotalUsers(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    

    public function findByEmail(string $email): ?UserInterface
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findByid(UserId $id): ?UserInterface
    {
        return $this->findOneBy(['id' => $id]);   
    }

    public function save(User $user): User
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    public function update(User $user): User
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    public function delete(User $user): void
    {
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();

    }

    public function getAllUsersRegistrationData(): array
    {
        $result = $this->createQueryBuilder('u')
            ->select('DATE_FORMAT(u.createdAt, \'%Y-%m-%d\') as date', 'COUNT(u.id) as userCount')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->getQuery()
            ->getResult();
        
        return $result;
    }
    
    public function searchUsers(string $search, int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        $searchTerm = '%' . $search . '%';
        
        return $this->createQueryBuilder('u')
            ->where('u.firstname LIKE :search')
            ->orWhere('u.lastname LIKE :search')
            ->orWhere('u.email LIKE :search')
            ->orWhere('u.socity LIKE :search')
            ->orWhere('u.phone LIKE :search')
            ->orWhere('CONCAT(u.firstname, \' \', u.lastname) LIKE :search')
            ->orWhere('CONCAT(u.lastname, \' \', u.firstname) LIKE :search')
            ->setParameter('search', $searchTerm)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('u.firstname', 'ASC')
            ->addOrderBy('u.lastname', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    public function getTotalUsersWithSearch(string $search): int
    {
        $searchTerm = '%' . $search . '%';
        
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.firstname LIKE :search')
            ->orWhere('u.lastname LIKE :search')
            ->orWhere('u.email LIKE :search')
            ->orWhere('u.socity LIKE :search')
            ->orWhere('u.phone LIKE :search')
            ->orWhere('CONCAT(u.firstname, \' \', u.lastname) LIKE :search')
            ->orWhere('CONCAT(u.lastname, \' \', u.firstname) LIKE :search')
            ->setParameter('search', $searchTerm)
            ->getQuery()
            ->getSingleScalarResult();
    }
}