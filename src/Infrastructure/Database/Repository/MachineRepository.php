<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Gateway\MachineRepositoryInterface;
class MachineRepository extends ServiceEntityRepository implements MachineRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, Machine::class);
    }
    // public function getAll(): array
    // {
    //     return $this->findAll();
    // }

    // public function findByEmail(string $email): ?MachineInterface
    // {
    //     return $this->findOneBy(['email' => $email]);
    // }

    // public function findByid(MachineId $id): ?MachineInterface
    // {
    //     return $this->findOneBy(['id' => $id]);   
    // }

    public function save(Machine $machine): Machine
    {
        $em = $this->getEntityManager();
        $em->persist($machine);
        $em->flush();

        return $machine;
    }

    // public function update(Machine $Machine): Machine
    // {
    //     $em = $this->getEntityManager();
    //     $em->persist($Machine);
    //     $em->flush();

    //     return $Machine;
    // }
}