<?php

namespace App\Utils\Csv;

use Illuminate\Validation\ValidationException;

interface Importer
{
    /**
     * @param array $headers
     *
     * @return void
     */
    public function setHeaders(array $headers);

    /**
     * @param array $rules
     *
     * @return void
     */
    public function setValidationRules(array $rules);

    /**
     * @param array $messages
     *
     * @return void
     */
    public function setValidationMessages(array $messages);

    /**
     * @param array $attributes
     *
     * @return void
     */
    public function setValidationAttributes(array $attributes);

    /**
     * 状態を初期化する。
     *
     * @return static
     */
    public function resetState();

    /**
     * CSVインポートをファイルパスを使用して行う。
     * 実際の保存処理はクロージャで行う。
     * エラーメッセージの配列と保存済みのデータの配列を返す。
     *
     * @param string $filePath
     * @param Closure $storeRow
     * @param bool $withHeader
     *
     * @return void
     */
    public function import(string $filePath, \Closure $storeRow, bool $withHeader = true);

    /**
     * @param \Exception|string $e
     * @param int $csvLine
     *
     * @return string
     */
    public function formatErrorReport($e, int $csvLine);

    /**
     * @param array $row
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function validate(array $row);
}
