<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\ParcMachine\Data\Model\ParcMachine;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;
use Domain\ParcMachine\Data\ObjectValue\ParcMachineId;
use Domain\User\Data\Model\User;

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
}