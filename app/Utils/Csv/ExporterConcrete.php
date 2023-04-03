<?php

namespace App\Utils\Csv;

use App\Utils\FileExporter;

class ExporterConcrete implements Exporter
{
    private array $headers = [];
    private string $outputEncoding = 'SJIS-win';

    /**
     * @param array $headers
     *
     * @return static
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param string $encodeing
     *
     * @return static
     */
    public function setOutputEncoding(string $encodeing)
    {
        $this->outputEncoding = $encodeing;

        return $this;
    }

    /**
     * @param \Closure $provider
     * @param string|resource $outputDest
     *
     * @return FileExporter
     */
    public function getExporter(\Closure $provider, $outputDest = 'php://output'): FileExporter
    {
        return FileExporter::make(
            function () use ($provider, $outputDest) {
                $stream = $this->createStream($provider, $outputDest);
                fclose($stream);
            }
        );
    }

    /**
     * @param \Closure $provider
     * @param string|resource $outputDest
     *
     * @return resource
     */
    public function createStream(\Closure $provider, $outputDest = 'php://output')
    {
        $stream = is_string($outputDest) ? fopen($outputDest, 'w') : $outputDest;

        if (!empty($this->headers)) {
            $this->putCsv($stream, array_values($this->headers));
        }

        $exporter = function ($row) use ($stream) {
            $data = [];

            foreach ($this->headers as $key => $name) {
                $data[] = $this->extractValue($row, $key);
            }

            $this->putCsv($stream, $data);
        };

        $provider($exporter);

        return $stream;
    }

    /**
     * @param resource $stream
     * @param array $row
     *
     * @return void
     */
    private function putCsv($stream, $fields)
    {
        fputcsv($stream, mb_convert_encoding($fields, $this->outputEncoding, 'UTF-8'));
    }

    /**
     * @param object|array $row
     * @param mixed $key
     *
     * @return mixed
     */
    private function extractValue($row, $key)
    {
        if (is_array($row)) {
            return $row[$key] ?? '';
        }

        return $row->{$key} ?? '';
    }
}
