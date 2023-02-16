<?php

namespace App\Exceptions\Auth;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidSecurityTokenException extends HttpException
{
    public function __construct(
        int        $statusCode = 404,
        string     $message = 'The security token provided does not exist or has expired.',
        \Throwable $previous = null,
        array      $headers = [],
        int        $code = 0
    )
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
