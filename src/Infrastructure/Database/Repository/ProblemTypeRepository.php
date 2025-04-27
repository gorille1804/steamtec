<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\DecisionTree\Data\Model\ProblemType;
use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;
use Domain\DecisionTree\Data\ObjectValue\CategoryId;
use Domain\DecisionTree\Gateway\ProblemTypeRepositoryInterface;

class ProblemTypeRepository extends ServiceEntityRepository implements ProblemTypeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProblemType::class);
    }

    /** @return ProblemType[] */
    public function findAllByCategory(CategoryId $categoryId): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.categoryId = :cat')
            ->setParameter('cat', $categoryId)
            ->orderBy('p.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findById(ProblemTypeId $id): ?ProblemType
    {
        return $this->find($id);
    }

    public function save(ProblemType $problemType): ProblemType
    {
        $em = $this->getEntityManager();
        $em->persist($problemType);
        $em->flush();
        return $problemType;
    }

    public function update(ProblemType $problemType): ProblemType
    {
        $this->getEntityManager()->flush();
        return $problemType;
    }

    public function delete(ProblemType $problemType): void
    {
        $em = $this->getEntityManager();
        $em->remove($problemType);
        $em->flush();
    }
}