<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\ParcMachine\Data\Model\ParcMachine;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;
use Domain\ParcMachine\Data\ObjectValue\ParcMachineId;
use Domain\User\Data\Model\User;
use Domain\User\Data\ObjectValue\UserId;

class ParcMachineRepository extends ServiceEntityRepository implements ParcMachineRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, ParcMachine::class);
    }
    
    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getTotalCount(UserId $userId): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->leftJoin('p.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();
    }
    

    public function findAllByUser(User $user): array
    {
        return $this->findBy(['user' => $user]);
    }
    

    public function findById(ParcMachineId $id):?ParcMachine
    {
        return $this->findOneBy(['id' => $id]);   
    }

    public function save(ParcMachine $ParcMachine): ParcMachine
    {
        $em = $this->getEntityManager();
        $em->persist($ParcMachine);
        $em->flush();

        return $ParcMachine;
    }

    public function delete(ParcMachine $ParcMachine): void
    {
        $em = $this->getEntityManager();
        $em->remove($ParcMachine);
        $em->flush();

    }

    public function update(ParcMachine $parcMachine): ParcMachine
    {
        $em = $this->getEntityManager();
        $em->persist($parcMachine);
        $em->flush();

        return $parcMachine;
    }

    public function getUsersParcRegistrationData(UserId $userId): array
    {
        $result = $this->createQueryBuilder('p')
            ->select('DATE_FORMAT(p.createdAt, \'%Y-%m-%d\') as date', 'COUNT(p.id) as parcCount')
            ->leftJoin('p.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->getQuery()
            ->getResult();
        
        return $result;
    }

    public function findMachinesReachingMaintenanceThreshold(): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.machine', 'm')
            ->where('p.currentHourUse > 0')
            ->getQuery()
            ->getResult();
    }
    
}