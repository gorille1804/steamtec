<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Gateway\MachineRepositoryInterface;
use Domain\Machine\Gateway\MachineInterface;
use Domain\Machine\Data\ObjectValue\MachineId;
use Domain\User\Data\Model\User;

class MachineRepository extends ServiceEntityRepository implements MachineRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, Machine::class);
    }
    
    public function getAll(int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        
        return $this->createQueryBuilder('m')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getTotalMachines(): int
    {
        return $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findAllByUser(User $user): array
    {
        if ($user === null) {
             return $this->createQueryBuilder('m')
                ->leftJoin('m.user', 'u') 
                ->where('u.id IS NULL')
                ->getQuery()
                ->getResult();
        }
        return $this->findBy(['user' => $user]);
    }
    

    public function findByid(MachineId $id): ?MachineInterface
    {
        return $this->findOneBy(['id' => $id]);   
    }

    public function save(Machine $machine): Machine
    {
        $em = $this->getEntityManager();
        $em->persist($machine);
        $em->flush();

        return $machine;
    }

    public function delete(Machine $machine): void
    {
        $em = $this->getEntityManager();
        $em->remove($machine);
        $em->flush();

    }

    public function getAllMachinesRegistrationData(): array
    {
        $result = $this->createQueryBuilder('m')
            ->select('DATE_FORMAT(m.createdAt, \'%Y-%m-%d\') as date', 'COUNT(m.id) as machineCount')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->getQuery()
            ->getResult();
        
        return $result;
    }
}