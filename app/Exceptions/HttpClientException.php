<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class HttpClientException extends HttpException
{
    protected $renderable = true;

    /**
     * @param int $statusCode the range of statusCode is between 400 to 499
     * @param string $message
     * @param \Throwable|null $previous
     * @param array $headers
     * @param int $code
     */
    public function __construct(int $statusCode = Response::HTTP_BAD_REQUEST, string $message = '', \Throwable $previous = null, array $headers = [], int $code = 0)
    {
        if (!$message) {
            $message = __('error.http_default.' . $statusCode) ?? 'エラーが発生しました。';
        }

        parent::__construct($statusCode, $message, $previous, $headers, $code);

        $this->setView('errors.'.Response::HTTP_BAD_REQUEST, []);
    }
}
