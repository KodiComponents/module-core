<?php

namespace KodiCMS\CMS\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Response;
use KodiCMS\API\Exceptions\Exception as APIException;
use KodiCMS\API\Http\Response as APIResponse;
use KodiCMS\CMS\Http\Controllers\ErrorController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends \App\Exceptions\Handler
{

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     *
     * @return void
     */
    public function report(Exception $e)
    {
        $this->dontReport[] = \Illuminate\Validation\ValidationException::class;

        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Exception                $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($request->ajax() or ($e instanceof APIException)) {
            return $this->renderApiException($e);
        }

        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }
        if (\CMS::isBackend()) {
            return $this->renderControllerException($e);
        }

        return parent::render($request, $e);
    }

    /**
     * @param Exception $e
     *
     * @return APIResponse
     */
    protected function renderApiException(Exception $e)
    {
        return (new APIResponse(config('app.debug')))->createExceptionResponse($e);
    }

    /**
     * Render an exception using ErrorController.
     *
     * @param  Exception $e
     *
     * @return \Illuminate\Http\Response
     */
    protected function renderControllerException(Exception $e)
    {
        $code = 500;

        if ($e instanceof HttpResponseException) {
            $code = $e->getStatusCode();
        } else if ($e->getCode() > 0) {
            $code = $e->getCode();
        }

        try {
            /** @var ErrorController $controller */
            $controller = app()->make(ErrorController::class);

            if (method_exists($controller, 'error'.$code)) {
                $action = 'error'.$code;
            } else {
                $action = 'errorDefault';
            }

            $response = $controller->callAction($action, [$e]);

            if (! ($response instanceof Response)) {
                $response = new Response($response);
            }

            return $this->toIlluminateResponse($response, $e);
        } catch (\Exception $ex) {
            return $this->toIlluminateResponse($this->convertExceptionToResponse($ex), $ex);
        }
    }
}
