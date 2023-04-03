<?php

namespace App\Utils\Csv;

use App\Exceptions\ErrorUtil;
use App\Exceptions\SystemException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ImporterConcrete implements Importer
{
    private array $headers = [];
    private array $validationRules = [];
    private array $validationMessages = [];
    private array $validationAttributes = [];

    /**
     * @param array $headers
     *
     * @return void
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param array $rules
     *
     * @return void
     */
    public function setValidationRules(array $rules)
    {
        $this->validationRules = $rules;
    }

    /**
     * @param array $messages
     *
     * @return void
     */
    public function setValidationMessages(array $messages)
    {
        $this->validationMessages = $messages;
    }

    /**
     * @param array $attributes
     *
     * @return void
     */
    public function setValidationAttributes(array $attributes)
    {
        $this->validationAttributes = $attributes;
    }

    /**
     * 状態を初期化する。
     *
     * @return static
     */
    public function resetState()
    {
        $this->headers = [];
        $this->validationRules = [];
        $this->validationMessages = [];
        $this->validationAttributes = [];

        return $this;
    }

    /**
     * CSVインポートをファイルパスを使用して行う。
     * 実際の保存処理はクロージャで行う。
     * エラーメッセージの配列と保存済みのデータの配列を返す。
     *
     * @param string $filePath
     * @param \Closure $storeRow
     * @param bool $withHeader
     *
     * @return void
     */
    public function import(string $filePath, \Closure $storeRow, bool $withHeader = true)
    {
        $stream = fopen($filePath, 'r');

        if ($stream === false) {
            throw new SystemException('ファイルを読み込めませんでした。');
        }

        $index = 0;

        while (($row = fgets($stream)) !== false) {
            try {
                $index++;

                $row = $this->mbConvertEncoding($row);

                $row = str_getcsv($row);

                if ($withHeader && $index === 1) {
                    continue;
                }

                if (empty($row)) {
                    continue;
                }

                $row = $this->preprocessRow($row);

                $storeRow($row, $index);
            } finally {
                unset($row);
                gc_collect_cycles();
            }
        }
    }

    /**
     * @param mixed $csv
     *
     * @return array
     */
    protected function preprocess($csv)
    {
        $csv = $this->mbConvertEncoding($csv);

        if (strpos($csv, "\r\n") !== false) {
            $csv = explode("\r\n", $csv);
        } else {
            $csv = explode("\n", $csv);
        }

        return $csv;
    }

    /**
     * @param string|array $row
     *
     * @return array
     */
    protected function preprocessRow($row)
    {
        $translated = [];

        foreach ($this->headers as $index => $column) {
            $value = $row[$index];
            $value = trim($value);
            $value = $value === '' ? null : $value;
            $translated[$column] = $value;
        }

        return $translated;
    }

    /**
     * @param \Exception|string $e
     * @param int $csvLine
     *
     * @return string
     */
    public function formatErrorReport($e, int $csvLine)
    {
        $message = '';

        if (is_string($e)) {
            $message = $e;
        } elseif ($e instanceof ValidationException) {
            $errors = $e->errors();
            $message = current($errors)[0];
        } else {
            ErrorUtil::report($e, 'Failed to import CSV.');
            $message = '予期せぬエラーが発生しました。';
        }

        return sprintf('%d行目: %s', $csvLine, $message);
    }

    /**
     * @param array $row
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function validate(array $row)
    {
        $validator = Validator::make(
            $row,
            $this->validationRules,
            $this->validationMessages,
            $this->validationAttributes
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function mbConvertEncoding(string $text)
    {
        return mb_convert_encoding($text, 'UTF-8', 'UTF-8,SJIS-win,SJIS');
    }
}
