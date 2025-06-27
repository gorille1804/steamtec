<?php

namespace Domain\Faq\Data\Contract;

class CreateFaqRequest
{
    public string $question;
    public string $answer;
    public bool $isActive = true;
}