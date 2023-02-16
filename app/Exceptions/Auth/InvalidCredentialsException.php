<?php

namespace App\Exceptions\Auth;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidCredentialsException extends HttpException
{
    public function __construct(
        int        $statusCode = 401,
        string     $message = 'Invalid credentials.',
        \Throwable $previous = null,
        array      $headers = [],
        int        $code = 0
    )
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
