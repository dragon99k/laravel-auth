<?php

namespace App\Utils;

class StopWatch
{
    private float $start = 0.0;
    private float $time = 0.0;

    /**
     * @return StopWatch
     */
    public function start(): StopWatch
    {
        $this->start = microtime(true);

        return $this;
    }

    /**
     * @return StopWatch
     */
    public function stop(): StopWatch
    {
        $this->time += microtime(true) - $this->start;

        return $this;
    }

    /**
     * @return StopWatch
     */
    public function reset(): StopWatch
    {
        $this->start = 0.0;
        $this->time = 0.0;

        return $this;
    }

    /**
     * @return float
     */
    public function getTime(): float
    {
        return $this->time;
    }
}
