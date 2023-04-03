<?php

namespace App\Exceptions\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

trait Renderable
{
    /**
     * @var bool
     */
    protected $renderable = false;

    /**
     * @var string
     */
    protected $viewName = 'errors.500';

    /**
     * @var array
     */
    protected $viewData = [];

    /**
     * @param bool $renderable
     *
     * @return static
     */
    public function setRenderable(bool $renderable)
    {
        $this->renderable = $renderable;

        return $this;
    }

    /**
     * @return bool
     */
    public function renderable()
    {
        return $this->renderable;
    }

    /**
     * @param string $name
     * @param array $data = []
     *
     * @return void
     */
    public function setView(string $name, array $data = [])
    {
        $this->viewName = $name;
        $this->viewData = $data;

        $errors = $this->viewData['errors'] ?? new \Illuminate\Support\MessageBag();
        $errors->add('global', $this->renderable() && $this->getMessage()
            ? $this->getMessage()
            : __('error.http_default.'.Response::HTTP_INTERNAL_SERVER_ERROR));

        $this->viewData['errors'] = $errors;

        return $this;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function createView()
    {
        return response()->view(
            $this->viewName,
            $this->viewData,
            $this instanceof HttpExceptionInterface ? $this->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function createJsonResponse()
    {
        return new JsonResponse(
            ['message' => $this->renderable() && $this->getMessage() ? $this->getMessage() : __('error.http_default.'.Response::HTTP_INTERNAL_SERVER_ERROR)],
            $this instanceof HttpExceptionInterface ? $this->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR,
            [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }
}
