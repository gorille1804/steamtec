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
            ->andWhere('d.parentStepId IS NULL')
            ->setParameter('pt', $problemTypeId)
            ->getQuery()
            ->getResult();
    }

    /** @return DiagnosticStep[] */
    public function findAllBySymptome(string $symptome): array
    {
        $em = $this->getEntityManager();
        $conn = $em->getConnection();

        $sql = "
            WITH RECURSIVE diagnostic_tree AS (
                SELECT id, parent_step_id, step_order as level
                FROM diagnostic_step
                WHERE id = :symptome
                UNION ALL
                SELECT ds.id, ds.parent_step_id, step_order as level
                FROM diagnostic_step ds
                JOIN diagnostic_tree dt ON ds.parent_step_id = dt.id
            )
            SELECT id, level
            FROM diagnostic_tree
            ORDER BY level
        ";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['symptome' => $symptome]);
        $ids = array_column($result->fetchAllAssociative(), 'id');

        if (empty($ids)) {
            return [];
        }

        return $this->findBy(['id' => $ids], ['stepOrder' => 'ASC']);
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