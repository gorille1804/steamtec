<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Chantier\Gateway\ChantierRepositoryInterface;
use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Chantier\Data\ObjectValue\ChantierId;

class ChantierRespository extends ServiceEntityRepository implements ChantierRepositoryInterface
{
    public function __construct(
        private readonly ManagerRegistry $registry
    )
    {
        parent::__construct($registry, Chantier::class);
    }


    public function getAll(): array
    {
        return $this->createQueryBuilder('c')
                    ->select('c, cm')
                    ->leftJoin('c.chantierMachines', 'cm')
                    ->getQuery()
                    ->getResult();
    }

    public function findById(ChantierId $id): ?Chantier
    {
        return $this->createQueryBuilder('c')
                    ->select('c, cm')
                    ->leftJoin('c.chantierMachines', 'cm')
                    ->where('c.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function findByCriteria(array $criteria): array
    {
        return $this->findBy($criteria);
    }

    public function save(Chantier $chantier): Chantier
    {
        $em = $this->getEntityManager();

        $em->persist($chantier);
        $em->flush();
        return $chantier;
    }

    public function update(Chantier $chantier): Chantier
    {
        $em = $this->getEntityManager();
        
        $em->persist($chantier);
        $em->flush();
        return $chantier;
    }

    public function delete(Chantier $chantier): void
    {
        $em = $this->getEntityManager();
        $em->remove($chantier);
        $em->flush();
    }

}