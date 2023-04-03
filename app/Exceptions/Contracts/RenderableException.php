<?php

namespace App\Exceptions\Contracts;

interface RenderableException extends \Throwable
{
    /**
     * @param bool $renderable
     *
     * @return static
     */
    public function setRenderable(bool $renderable);

    /**
     * @return bool
     */
    public function renderable();

    /**
     * @param string $name
     * @param array $data = []
     *
     * @return void
     */
    public function setView(string $name, array $data = []);

    /**
     * @return \Illuminate\Http\Response
     */
    public function createView();

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function createJsonResponse();
}
