<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Entretien\Data\Model\EntretienLog;
use Domain\Entretien\Data\ObjectValue\EntretienLogId;
use Domain\Entretien\Gateway\EntretienLogRepositoryInterface;
use Domain\ParcMachine\Data\Model\ParcMachine;
use Domain\User\Data\Model\User;
use Domain\Machine\Data\Model\Machine;

class EntretienLogRepository extends ServiceEntityRepository implements EntretienLogRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntretienLog::class);
    }

    public function save(EntretienLog $entretienLog): EntretienLog
    {
        $em = $this->getEntityManager();
        $em->persist($entretienLog);
        $em->flush();

        return $entretienLog;
    }

    public function findById(EntretienLogId $id): ?EntretienLog
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findAllByUser(User $user): array
    {
        return $this->createQueryBuilder('el')
            ->join('el.parcMachine', 'pm')
            ->where('pm.user = :user')
            ->setParameter('user', $user)
            ->orderBy('el.logDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllByMachine(Machine $machine): array
    {
        return $this->createQueryBuilder('el')
            ->join('el.parcMachine', 'pm')
            ->where('pm.machine = :machine')
            ->setParameter('machine', $machine)
            ->orderBy('el.logDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllByUserAndMachine(User $user, Machine $machine): array
    {
        return $this->createQueryBuilder('el')
            ->join('el.parcMachine', 'pm')
            ->where('pm.user = :user')
            ->andWhere('pm.machine = :machine')
            ->setParameter('user', $user)
            ->setParameter('machine', $machine)
            ->orderBy('el.logDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllByParcMachine(ParcMachine $parcMachine): array
    {
        return $this->findBy(['parcMachine' => $parcMachine], ['logDate' => 'DESC']);
    }

    public function delete(EntretienLog $entretienLog): void
    {
        $em = $this->getEntityManager();
        $em->remove($entretienLog);
        $em->flush();
    }

    public function update(EntretienLog $entretienLog): EntretienLog
    {
        $em = $this->getEntityManager();
        $em->persist($entretienLog);
        $em->flush();

        return $entretienLog;
    }
} 