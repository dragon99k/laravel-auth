<?php

namespace App\Exceptions;

use App\Exceptions\Concerns\Redirectable;
use App\Exceptions\Concerns\Renderable;
use App\Exceptions\Concerns\Reportable;
use App\Exceptions\Concerns\RetrivePrevious;
use App\Exceptions\Contracts\RedirectableException;
use App\Exceptions\Contracts\RenderableException;
use App\Exceptions\Contracts\ReportableException;

abstract class Exception extends \RuntimeException implements ReportableException, RedirectableException, RenderableException
{
    use Reportable,
        RetrivePrevious,
        Redirectable,
        Renderable;

    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
