<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class HttpBadRequestException extends HttpClientException
{
    public function __construct(string $message = '', \Throwable $previous = null, array $headers = [], int $code = 0)
    {
        $statusCode = Response::HTTP_BAD_REQUEST;

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
