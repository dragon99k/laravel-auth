<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class HttpAuthException extends HttpClientException
{
    public function __construct(string $message = '', \Throwable $previous = null, array $headers = [], int $code = 0)
    {
        $statusCode = Response::HTTP_UNAUTHORIZED;

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
