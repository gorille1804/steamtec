<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Domain\Chantier\Gateway\ChantierMachineRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Chantier\Data\Model\ChantierMachine\ChantierMachine;
use Domain\Chantier\Data\ObjectValue\ChantierMachineId;

class ChantierMachineRepository extends ServiceEntityRepository implements ChantierMachineRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChantierMachine::class);
    }


    public function findById(ChantierMachineId $id): ?array
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findByCriteria(array $criteria): array
    {
        return $this->findBy($criteria);
    }
    public function save(ChantierMachine $chantierMachine): ChantierMachine
    {
        $em = $this->getEntityManager();
        $em->persist($chantierMachine);
        $em->flush();
        
        return $chantierMachine;
    }

    public function delete(ChantierMachine $chantierMachine): void
    {
        $em = $this->getEntityManager();
        $em->remove($chantierMachine);
        $em->flush();
    }
    
}