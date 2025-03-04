<?php

namespace Domain\Faq\Exception;

class FaqNotFoundException extends \Exception
{
    public function __construct(string $message = 'FAQ not found')
    {
        parent::__construct($message);
    }
}