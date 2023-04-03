<?php

namespace App\Exceptions\Concerns;

trait RetrivePrevious
{
    /**
     * @param string $class
     *
     * @return \Throwable|null
     */
    public function retrivePrivious(string $class)
    {
        if ($this instanceof $class) {
            return $this;
        }

        $previous = $this;

        while ($previous = $previous->getPrevious()) {
            if ($previous instanceof $class) {
                return $previous;
            }
        }

        return $previous;
    }

    /**
     * @return \Throwable
     */
    public function getOriginalError()
    {
        $exeption = $this;

        while ($previous = $exeption->getPrevious()) {
            $exeption = $previous;
        }

        return $exeption;
    }
}
