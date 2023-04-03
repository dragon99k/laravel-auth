<?php

namespace App\Exceptions\Concerns;

use App\Exceptions\ErrorUtil;
use App\Exceptions\SystemException;
use App\Utils\Log;

trait Reportable
{
    /**
     * @var int
     */
    protected $logLevel = Log::ERROR;

    /**
     * @var string
     */
    protected $contextType = ErrorUtil::ERROR_TYPE_GENERAL;

    /**
     * @var array
     */
    protected $contextParams = [];

    /**
     * ログを抑止する
     *
     * @var bool
     */
    protected $suppress = false;

    /**
     * @param array $context
     * @param string|null $type
     *
     * @return static
     */
    public function attachContext(array $context, ?string $type = null)
    {
        $this->contextParams = array_merge($this->contextParams, $context);

        if (isset($type)) {
            $this->contextType = $type;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->contextParams;
    }

    /**
     * @param string $type
     *
     * @return static
     */
    public function setContextType(string $type)
    {
        $this->contextType = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getContextType()
    {
        return $this->contextType;
    }

    /**
     * @param int $level
     *
     * @return static
     */
    public function setLogLevel(int $level)
    {
        if (Log::isValidLevel($level) === false) {
            throw new SystemException('The specified log level of "' . ((string) $level) . '" is invalid');
        }

        $this->logLevel = $level;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * @return string
     */
    public function getLogLevelName()
    {
        return Log::getLevelName($this->logLevel);
    }

    /**
     * @param bool $value
     *
     * @return static
     */
    public function suppress(bool $value)
    {
        $this->suppress = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function suppressed()
    {
        return $this->suppress;
    }

    /**
     * ログの出力
     *
     * @return void
     */
    public function report()
    {
        ErrorUtil::report($this, null, [], ['logged_from' => false]);
    }
}
