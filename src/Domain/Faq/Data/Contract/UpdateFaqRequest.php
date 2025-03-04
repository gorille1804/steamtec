<?php

namespace Domain\Faq\Data\Contract;

use Domain\FAQ\Data\ObjectValue\FAQId;

class UpdateFaqRequest
{
    public string $question;
    public string $answer;
    public bool $isActive;
    
}