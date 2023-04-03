<?php

namespace App\Exceptions\Contracts;

interface ReportableException extends \Throwable
{
    /**
     * @param array $context
     * @param string|null $type
     *
     * @return static
     */
    public function attachContext(array $context, ?string $type = null);

    /**
     * @return array
     */
    public function getContext();

    /**
     * @param string $type
     *
     * @return static
     */
    public function setContextType(string $type);

    /**
     * @return string
     */
    public function getContextType();

    /**
     * @param int $level
     *
     * @return static
     */
    public function setLogLevel(int $level);

    /**
     * @return string
     */
    public function getLogLevel();

    /**
     * @return string
     */
    public function getLogLevelName();

    /**
     * ログの抑止
     *
     * @param bool $value
     *
     * @return static
     */
    public function suppress(bool $value);

    /**
     * ログの抑止
     *
     * @return bool
     */
    public function suppressed();

    /**
     * ログの出力
     *
     * @return void
     */
    public function report();
}
