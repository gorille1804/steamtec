<?php

namespace Infrastructure\Database\Repository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\MachineLog\Gateway\MachineLogRepositoryInterface;
use Domain\MachineLog\Data\Model\MachineLog;
use Domain\ParcMachine\Data\ObjectValue\ParcMachineId;

class MachineLogRepository extends ServiceEntityRepository implements MachineLogRepositoryInterface
{
    public function __construct(
        private readonly ManagerRegistry $registry
    ){
        parent::__construct($registry, MachineLog::class);
    }

    public function findByParcMachineId(ParcMachineId $parcMachineId): array
    {
        return $this->findBy(['parcMachine' => $parcMachineId]);
    }

    public function create(MachineLog $machineLog): MachineLog
    {
        $em = $this->getEntityManager();
        $em->persist($machineLog);
        $em->flush();

        return $machineLog;
    }

    public function update(MachineLog $machineLog): MachineLog
    {
        $em = $this->getEntityManager();
        $em->persist($machineLog);
        $em->flush();

        return $machineLog;
    }

    public function delete(MachineLog $machineLog): void
    {
        $em = $this->getEntityManager();
        $em->remove($machineLog);
        $em->flush();
    }
}