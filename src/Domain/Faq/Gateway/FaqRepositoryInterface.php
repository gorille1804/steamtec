<?php

namespace Domain\Faq\Gateway;

use Domain\Faq\Data\ObjectValue\FaqId;
use Domain\Faq\Data\Model\Faq;

interface FaqRepositoryInterface
{
    public function getAll(int $page = 1, int $limit =  10): array;
    public function getTotalFaqs(): int;
    public function findById(FaqId $id): ?Faq;
    public function save(Faq $faq): Faq;
    public function update(Faq $faq): Faq;
    public function delete(Faq $faq): void;
    public function getAllActive():array;
}