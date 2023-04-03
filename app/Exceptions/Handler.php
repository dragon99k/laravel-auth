<?php

namespace App\Exceptions;

use App\Exceptions\Contracts\RedirectableException;
use App\Exceptions\Contracts\RenderableException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Reflector;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
    ];

    /**
     * A list of the internal exception types that should not be reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $internalDontReport = [
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * @var array
     */
    private $renderExceptionMap = [
        ModelNotFoundException::class => HttpNotFoundException::class,
        MethodNotAllowedHttpException::class => HttpNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * @param \Throwable $e
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function report(\Throwable $e)
    {
        $e = $this->mapException($e);

        if ($this->shouldntReport($e)) {
            return;
        }

        if (Reflector::isCallable($reportCallable = [$e, 'report']) &&
            $this->container->call($reportCallable) !== false) {
            return;
        }

        foreach ($this->reportCallbacks as $reportCallback) {
            if ($reportCallback->handles($e) && $reportCallback($e) === false) {
                return;
            }
        }

        ErrorUtil::report($e);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (RenderableException $e, $request) {
            return $this->shouldReturnJson($request, $e)
                ? $e->createJsonResponse()
                : $e->createView();
        });
        $this->renderable(function (HttpExceptionInterface $e, $request) {
            $view = view()->exists('errors.'.$e->getStatusCode()) ? 'errors.'.$e->getStatusCode() : 'errors.500';
            $errors = new \Illuminate\Support\MessageBag();
            $errors->add('global', __('error.http_default.'.$e->getStatusCode()));

            return $this->shouldReturnJson($request, $e)
                ? $this->prepareJsonResponse($request, $e)
                : response()->view($view, ['errors' => $errors], $e->getStatusCode());
        });
        // $this->renderable(function (AuthorizationException $e, Request $request) {
        //     return $this->shouldReturnJson($request, $e)
        //         ? response()->json(['message' => 'リクエストされた操作はご利用になれません。'], Response::HTTP_FORBIDDEN)
        //         : response()->view('auth.auth.login', [], Response::HTTP_FORBIDDEN);
        // });
        $this->renderable(function (AuthenticationException $e, Request $request) {
            return $this->shouldReturnJson($request, $e)
                ? response()->json(['message' => 'リクエストされた操作はご利用になれません。'], Response::HTTP_UNAUTHORIZED)
                : response()->view('auth.auth.login', [], Response::HTTP_UNAUTHORIZED);
        });
        // $this->renderable(function (Throwable $e, Request $request) {
        //     return $this->shouldReturnJson($request, $e)
        //         ? response()->json(['message' => __('error.http_default.'.Response::HTTP_INTERNAL_SERVER_ERROR)], Response::HTTP_INTERNAL_SERVER_ERROR)
        //         : response()->view('errors.500', ['errorMessage' => __('error.http_default.'.Response::HTTP_INTERNAL_SERVER_ERROR)], Response::HTTP_INTERNAL_SERVER_ERROR);
        // });
    }

    /**
     * @param mixed $request
     * @param \Throwable $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, \Throwable $e)
    {
        // renderableが反応しないのでここで処理する。
        if ($e instanceof AuthorizationException) {
            return $this->shouldReturnJson($request, $e)
                ? response()->json(['message' => __('error.unauthorized')], Response::HTTP_UNAUTHORIZED)
                : response()->view('auth.auth.login', [], Response::HTTP_UNAUTHORIZED);
        }

        if ($this->shouldRedirect($e)) {
            /** @var RedirectableException */
            $e = $e;

            return $e->redirectResponse();
        }

        $e = $this->replaceHttpException($e);

        return parent::render($request, $e);
    }

    /**
     * @param \Throwable $e
     *
     * @return bool
     */
    private function shouldRedirect(\Throwable $e)
    {
        return $e instanceof RedirectableException && $e->hasRedirectTo();
    }

    /**
     * @param \Throwable $e
     *
     * @return \Throwable
     */
    private function replaceHttpException(\Throwable $e)
    {
        foreach ($this->renderExceptionMap as $class => $newClass) {
            if ($e instanceof $class) {
                return new $newClass;
            }
        }

        return $e;
    }
}
