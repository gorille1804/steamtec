<?php

namespace Domain\Contact\UseCase;

use Domain\Contact\Data\Contract\ContactRequest;

interface SendContactMailUseCaseInterface
{
    public function __invoke(ContactRequest $request): void;
}