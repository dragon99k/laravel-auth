<?php

namespace App\Exceptions\Concerns;

use App\Exceptions\Contracts\RenderableException;
use Illuminate\Http\Response;

trait Redirectable
{
    /**
     * @var string|null
     */
    protected $redirectTo;

    /**
     * @var int
     */
    protected $redirectStatusCode;

    /**
     * @param string $redirectTo
     *
     * @return static
     */
    public function setRedirect(string $redirectTo, ?int $statusCode = null)
    {
        $this->redirectTo = $redirectTo;
        $this->redirectStatusCode = $statusCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function redirectTo()
    {
        return $this->redirectTo;
    }

    /**
     * @return bool
     */
    public function hasRedirectTo()
    {
        return isset($this->redirectTo);
    }

    /**
     * @return string|null
     */
    public function redirectResponse()
    {
        if ($this instanceof RenderableException && $this->renderable() && $this->getMessage()) {
            session()->put(config('session.keys.redirect_error_message'), $this->getMessage());
        }

        return redirect(
            route($this->redirectTo),
            $this->redirectStatusCode ?? Response::HTTP_TEMPORARY_REDIRECT
        );
    }
}
