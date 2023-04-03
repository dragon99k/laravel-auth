<?php

namespace App\Utils;

class FileExporter
{
    private \Closure $emitter;
    private ?string $fileName;
    private ?string $contentType;

    /**
     * @param \Closure $emitter
     * @param string|null $fileName
     * @param string|null $contentType
     */
    public function __construct(\Closure $emitter, ?string $fileName = null, ?string $contentType = null)
    {
        $this->emitter = $emitter;
        $this->fileName = $fileName;
        $this->contentType = $contentType;
    }

    /**
     * @param \Closure $emitter
     * @param string|null $fileName
     * @param string|null $contentType
     *
     * @return static
     */
    public static function make(\Closure $emitter, ?string $fileName = null, ?string $contentType = null): FileExporter
    {
        return new static($emitter, $fileName, $contentType);
    }

    /**
     * @return array
     */
    public function getHttpHeaders(): array
    {
        return [
            'Content-type' => $this->contentType,
            'Content-Disposition' => sprintf('attachment; filename="%s";', urlencode($this->fileName)),
            'Access-Control-Expose-Headers' => 'Content-Disposition',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
    }

    /**
     * @param string $fileName
     *
     * @return FileExporter
     */
    public function setFileName(string $fileName): FileExporter
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @param string $contentType
     *
     * @return FileExporter
     */
    public function setContentType(string $contentType): FileExporter
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * @return string
     */
    public function fileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function contentType(): string
    {
        return $this->contentType;
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        return $this->export();
    }

    /**
     * @return mixed
     */
    private function export()
    {
        $emitter = $this->emitter;

        return $emitter($this);
    }
}
