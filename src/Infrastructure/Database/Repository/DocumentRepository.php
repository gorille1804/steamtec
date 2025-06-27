<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\LazyServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Document\Gateway\DocumentRepositoryInterface;
use Domain\Document\Data\Model\Document;

class DocumentRepository extends LazyServiceEntityRepository implements DocumentRepositoryInterface
{
    public function __construct(
       private readonly ManagerRegistry $managerRegistry
    )
    {
        parent::__construct($managerRegistry, Document::class);
    }

    public function create(Document $document): Document
    {
        $em = $this->managerRegistry->getManager();
        $em->persist($document);
        $em->flush();
        return $document;
    }

    public function delete(Document $document): void
    {
        $em = $this->managerRegistry->getManager();
        $em->remove($document);
        $em->flush();
    }
}