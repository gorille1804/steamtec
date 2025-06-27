<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Chantier\Gateway\ChantierRepositoryInterface;
use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Chantier\Data\ObjectValue\ChantierId;
use Domain\User\Data\ObjectValue\UserId;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChantierRespository extends ServiceEntityRepository implements ChantierRepositoryInterface
{
    public function __construct(
        private readonly ManagerRegistry $registry,
        private readonly TranslatorInterface $translator,
    )
    {
        parent::__construct($registry, Chantier::class);
    }


    public function getAll(): array
    {
        return $this->createQueryBuilder('c')
                    ->select('c, cm')
                    ->leftJoin('c.chantierMachines', 'cm')
                    ->orderBy('c.chantierDate', 'DESC')
                    ->getQuery()
                    ->getResult();
    }

    public function getAllWithPagination(int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        return $this->createQueryBuilder('c')
                    ->select('c, cm')
                    ->leftJoin('c.chantierMachines', 'cm')
                    ->orderBy('c.chantierDate', 'DESC')
                    ->setFirstResult($offset)
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }

    public function getTotalChantiers(): int
    {
        return $this->createQueryBuilder('c')
                    ->select('COUNT(c.id)')
                    ->getQuery()
                    ->getSingleScalarResult();
    }
    
    public function findById(ChantierId $id): ?Chantier
    {
        return $this->createQueryBuilder('c')
                    ->select('c, cm, ml')
                    ->leftJoin('c.chantierMachines', 'cm')
                    ->where('c.id = :id')
                    ->leftJoin('c.machineLogs', 'ml')
                    ->where('c.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function findByUser(UserId $userId, int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        return $this->createQueryBuilder('c')
            ->select('c, cm, u')
            ->leftJoin('c.chantierMachines', 'cm')
            ->leftJoin('c.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('c.chantierDate', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByUserWithSearch(UserId $userId, string $search = '', int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        
        if (empty($search)) {
            // Si pas de recherche, utiliser la méthode existante
            return $this->findByUser($userId, $page, $limit);
        }

        $sql = "
            SELECT DISTINCT c.id
            FROM chantiers c
            LEFT JOIN chantier_machines cm ON c.id = cm.chantier_id
            LEFT JOIN parc_machine pm ON cm.parc_machine_id = pm.id
            LEFT JOIN machine m ON pm.machine_id = m.id
            LEFT JOIN users u ON c.user_id = u.id
            WHERE u.id = :userId
            AND (
                lower(c.name) LIKE :search
                OR lower(c.surface_types) LIKE :search
                OR lower(c.rendement) LIKE :search
                OR lower(c.surface) LIKE :search
                OR lower(c.duration) LIKE :search
                OR lower(c.commentaire) LIKE :search
                OR lower(m.nom) LIKE :search
                OR lower(m.numero_identification) LIKE :search
                OR JSON_SEARCH(lower(c.materials), 'one', :search) IS NOT NULL
                OR DATE_FORMAT(c.chantier_date, '%d/%m/%Y') LIKE :search
                OR DATE_FORMAT(c.chantier_date, '%Y-%m-%d') LIKE :search
                OR c.encrassement_level LIKE :search
                OR c.vetuste_level LIKE :search
                OR lower(u.firstname) LIKE :search
                OR lower(u.lastname) LIKE :search
                OR lower(u.email) LIKE :search
                OR CASE 
                    WHEN c.encrassement_level = 1 THEN 'peu sale'
                    WHEN c.encrassement_level = 2 THEN 'moyennement sale'
                    WHEN c.encrassement_level = 3 THEN 'très sale'
                    ELSE ''
                  END LIKE :search
                OR CASE 
                    WHEN c.vetuste_level = 1 THEN 'récent'
                    WHEN c.vetuste_level = 2 THEN 'état d\'usage'
                    WHEN c.vetuste_level = 3 THEN 'très ancien'
                    ELSE ''
                  END LIKE :search
            )
            ORDER BY c.chantier_date DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->bindValue('userId', $userId->getValue());
        $stmt->bindValue('search', '%' . strtolower($search) . '%');
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, \PDO::PARAM_INT);
        $result = $stmt->executeQuery();

        $results = $result->fetchAllAssociative();
        
        // Récupérer les entités complètes avec leurs relations
        $chantiers = [];
        foreach ($results as $row) {
            $chantier = $this->createQueryBuilder('c')
                ->select('c, cm, pm, m, u')
                ->leftJoin('c.chantierMachines', 'cm')
                ->leftJoin('cm.parcMachine', 'pm')
                ->leftJoin('pm.machine', 'm')
                ->leftJoin('c.user', 'u')
                ->where('c.id = :id')
                ->setParameter('id', $row['id'])
                ->getQuery()
                ->getOneOrNullResult();
                
            if ($chantier) {
                $chantiers[] = $chantier;
            }
        }
        
        return $chantiers;
    }

    public function getTotalByUserWithSearch(UserId $userId, string $search = ''): int
    {
        if (empty($search)) {
            // Si pas de recherche, utiliser la méthode existante
            return $this->getTotalChantiers();
        }

        $sql = "
            SELECT COUNT(DISTINCT c.id)
            FROM chantiers c
            LEFT JOIN chantier_machines cm ON c.id = cm.chantier_id
            LEFT JOIN parc_machine pm ON cm.parc_machine_id = pm.id
            LEFT JOIN machine m ON pm.machine_id = m.id
            LEFT JOIN users u ON c.user_id = u.id
            WHERE u.id = :userId
            AND (
                lower(c.name) LIKE :search
                OR lower(c.surface_types) LIKE :search
                OR lower(c.rendement) LIKE :search
                OR lower(c.surface) LIKE :search
                OR lower(c.duration) LIKE :search
                OR lower(c.commentaire) LIKE :search
                OR lower(m.nom) LIKE :search
                OR lower(m.numero_identification) LIKE :search
                OR JSON_SEARCH(lower(c.materials), 'one', :search) IS NOT NULL
                OR DATE_FORMAT(c.chantier_date, '%d/%m/%Y') LIKE :search
                OR DATE_FORMAT(c.chantier_date, '%Y-%m-%d') LIKE :search
                OR c.encrassement_level LIKE :search
                OR c.vetuste_level LIKE :search
                OR lower(u.firstname) LIKE :search
                OR lower(u.lastname) LIKE :search
                OR lower(u.email) LIKE :search
                OR CASE 
                    WHEN c.encrassement_level = 1 THEN 'peu sale'
                    WHEN c.encrassement_level = 2 THEN 'moyennement sale'
                    WHEN c.encrassement_level = 3 THEN 'très sale'
                    ELSE ''
                  END LIKE :search
                OR CASE 
                    WHEN c.vetuste_level = 1 THEN 'récent'
                    WHEN c.vetuste_level = 2 THEN 'état d\'usage'
                    WHEN c.vetuste_level = 3 THEN 'très ancien'
                    ELSE ''
                  END LIKE :search
            )
        ";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->bindValue('userId', $userId->getValue());
        $stmt->bindValue('search', '%' . $search . '%');
        $result = $stmt->executeQuery();

        return (int) $result->fetchOne();
    }

    public function findAllWithSearch(string $search = '', int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        
        if (empty($search)) {
            // Si pas de recherche, utiliser la méthode existante
            return $this->getAllWithPagination($page, $limit);
        }

        $sql = "
            SELECT DISTINCT c.id
            FROM chantiers c
            LEFT JOIN chantier_machines cm ON c.id = cm.chantier_id
            LEFT JOIN parc_machine pm ON cm.parc_machine_id = pm.id
            LEFT JOIN machine m ON pm.machine_id = m.id
            LEFT JOIN users u ON c.user_id = u.id
            WHERE (
                lower(c.name) LIKE :search
                OR lower(c.surface_types) LIKE :search
                OR lower(c.rendement) LIKE :search
                OR lower(c.surface) LIKE :search
                OR lower(c.duration) LIKE :search
                OR lower(c.commentaire) LIKE :search
                OR lower(m.nom) LIKE :search
                OR lower(m.numero_identification) LIKE :search
                OR JSON_SEARCH(lower(c.materials), 'one', :search) IS NOT NULL
                OR DATE_FORMAT(c.chantier_date, '%d/%m/%Y') LIKE :search
                OR DATE_FORMAT(c.chantier_date, '%Y-%m-%d') LIKE :search
                OR c.encrassement_level LIKE :search
                OR c.vetuste_level LIKE :search
                OR lower(u.firstname) LIKE :search
                OR lower(u.lastname) LIKE :search
                OR lower(u.email) LIKE :search
                OR CASE 
                    WHEN c.encrassement_level = 1 THEN 'peu sale'
                    WHEN c.encrassement_level = 2 THEN 'moyennement sale'
                    WHEN c.encrassement_level = 3 THEN 'très sale'
                    ELSE ''
                  END LIKE :search
                OR CASE 
                    WHEN c.vetuste_level = 1 THEN 'récent'
                    WHEN c.vetuste_level = 2 THEN 'état d\'usage'
                    WHEN c.vetuste_level = 3 THEN 'très ancien'
                    ELSE ''
                  END LIKE :search
            )
            ORDER BY c.chantier_date DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->bindValue('search', '%' . strtolower($search) . '%');
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, \PDO::PARAM_INT);
        $result = $stmt->executeQuery();

        $results = $result->fetchAllAssociative();
        
        // Récupérer les entités complètes avec leurs relations
        $chantiers = [];
        foreach ($results as $row) {
            $chantier = $this->createQueryBuilder('c')
                ->select('c, cm, pm, m, u')
                ->leftJoin('c.chantierMachines', 'cm')
                ->leftJoin('cm.parcMachine', 'pm')
                ->leftJoin('pm.machine', 'm')
                ->leftJoin('c.user', 'u')
                ->where('c.id = :id')
                ->setParameter('id', $row['id'])
                ->getQuery()
                ->getOneOrNullResult();
                
            if ($chantier) {
                $chantiers[] = $chantier;
            }
        }
        
        return $chantiers;
    }

    public function getTotalWithSearch(string $search = ''): int
    {
        if (empty($search)) {
            // Si pas de recherche, utiliser la méthode existante
            return $this->getTotalChantiers();
        }

        $sql = "
            SELECT COUNT(DISTINCT c.id)
            FROM chantiers c
            LEFT JOIN chantier_machines cm ON c.id = cm.chantier_id
            LEFT JOIN parc_machine pm ON cm.parc_machine_id = pm.id
            LEFT JOIN machine m ON pm.machine_id = m.id
            LEFT JOIN users u ON c.user_id = u.id
            WHERE (
                lower(c.name) LIKE :search
                OR lower(c.surface_types) LIKE :search
                OR lower(c.rendement) LIKE :search
                OR lower(c.surface) LIKE :search
                OR lower(c.duration) LIKE :search
                OR lower(c.commentaire) LIKE :search
                OR lower(m.nom) LIKE :search
                OR lower(m.numero_identification) LIKE :search
                OR JSON_SEARCH(lower(c.materials), 'one', :search) IS NOT NULL
                OR DATE_FORMAT(c.chantier_date, '%d/%m/%Y') LIKE :search
                OR DATE_FORMAT(c.chantier_date, '%Y-%m-%d') LIKE :search
                OR c.encrassement_level LIKE :search
                OR c.vetuste_level LIKE :search
                OR lower(u.firstname) LIKE :search
                OR lower(u.lastname) LIKE :search
                OR lower(u.email) LIKE :search
                OR CASE 
                    WHEN c.encrassement_level = 1 THEN 'peu sale'
                    WHEN c.encrassement_level = 2 THEN 'moyennement sale'
                    WHEN c.encrassement_level = 3 THEN 'très sale'
                    ELSE ''
                  END LIKE :search
                OR CASE 
                    WHEN c.vetuste_level = 1 THEN 'récent'
                    WHEN c.vetuste_level = 2 THEN 'état d\'usage'
                    WHEN c.vetuste_level = 3 THEN 'très ancien'
                    ELSE ''
                  END LIKE :search
            )
        ";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->bindValue('search', '%' . $search . '%');
        $result = $stmt->executeQuery();

        return (int) $result->fetchOne();
    }

    public function findByCriteria(array $criteria): array
    {
        return $this->findBy($criteria);
    }

    public function save(Chantier $chantier): Chantier
    {
        $exists = $this->isAlredyExists($chantier);
        if ($exists) {
            throw new \Exception($this->translator->trans('chantiers.messages.error_alredy_exist', [
                '%name%' => $chantier->name
            ]));
        }

        $em = $this->getEntityManager();

        $em->persist($chantier);
        $em->flush();
        return $chantier;
    }

    public function update(Chantier $chantier): Chantier
    {
        $existingChantiers = $this->findByCriteria([
            'name' => $chantier->name,
            'user' => $chantier->user
        ]);

        // Vérifier s'il existe d'autres chantiers avec le même nom (exclure le chantier actuel)
        foreach ($existingChantiers as $existingChantier) {
            if ($existingChantier->id->getValue() !== $chantier->id->getValue()) {
                throw new \Exception($this->translator->trans('chantiers.messages.error_alredy_exist', [
                    '%name%' => $chantier->name
                ]));
            }
        }

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

    private function isAlredyExists(Chantier $chantier): array
    {
        return $this->findByCriteria([
            'name' => $chantier->name,
            'user' => $chantier->user
        ]);
    }

}