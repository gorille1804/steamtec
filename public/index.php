<?php

use Infrastructure\Symfony\Kernel;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

Request::setTrustedProxies(
    ['127.0.0.1', '104.199.26.50'], 
    Request::HEADER_X_FORWARDED_FOR | 
    Request::HEADER_X_FORWARDED_PORT | 
    Request::HEADER_X_FORWARDED_PROTO | 
    Request::HEADER_X_FORWARDED_HOST
);

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
