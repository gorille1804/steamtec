<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\DecisionTree\Data\Model\DiagnosticStep;
use Domain\DecisionTree\Data\ObjectValue\DiagnosticStepId;
use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;
use Domain\DecisionTree\Gateway\DiagnosticStepRepositoryInterface;

class DiagnosticStepRepository extends ServiceEntityRepository implements DiagnosticStepRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiagnosticStep::class);
    }

    /** @return DiagnosticStep[] */
    public function findAllByProblemType(ProblemTypeId $problemTypeId): array
    {
        return $this->createQueryBuilder('d')
            ->where('d.problemTypeId = :pt')
            ->setParameter('pt', $problemTypeId)
            ->getQuery()
            ->getResult();
    }

    public function findById(DiagnosticStepId $id): ?DiagnosticStep
    {
        return $this->find($id);
    }

    public function save(DiagnosticStep $diagnosticStep): DiagnosticStep
    {
        $em = $this->getEntityManager();
        $em->persist($diagnosticStep);
        $em->flush();
        return $diagnosticStep;
    }

    public function update(DiagnosticStep $diagnosticStep): DiagnosticStep
    {
        $this->getEntityManager()->flush();
        return $diagnosticStep;
    }

    public function delete(DiagnosticStep $diagnosticStep): void
    {
        $em = $this->getEntityManager();
        $em->remove($diagnosticStep);
        $em->flush();
    }
}