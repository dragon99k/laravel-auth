<?php

namespace App\Exceptions\Contracts;

interface RedirectableException extends \Throwable
{
    /**
     * @param string $redirectTo
     *
     * @return static
     */
    public function setRedirect(string $redirectTo);

    /**
     * Get the path the user should be redirected to.
     *
     * @return string|null
     */
    public function redirectTo();

    /**
     * @return bool
     */
    public function hasRedirectTo();

    /**
     * @return string|null
     */
    public function redirectResponse();
}
