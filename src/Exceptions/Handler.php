<?php

namespace KodiCMS\CMS\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use KodiCMS\API\Http\Response as APIResponse;
use KodiCMS\CMS\Http\Controllers\ErrorController;

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
        $this->dontReport[] = ValidationException::class;

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
        if ($request->ajax() or ($e instanceof \KodiCMS\API\Exceptions\Exception)) {
            return $this->sendResponseForApiException($e);
        }

        if (\CMS::isBackend()) {
            if ($e instanceof ModelNotFoundException) {
                return $this->sendResponseForModelNotFound($e);
            }

            if ($e instanceof ValidationException) {
                return $this->sendResponseForValidationError($request, $e);
            }

            return $this->renderControllerException($e);
        }

        return parent::render($request, $e);
    }

    /**
     * @param Exception $e
     *
     * @return APIResponse
     */
    protected function sendResponseForApiException(Exception $e)
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

    /**
     * @param ModelNotFoundException $e
     *
     * @return Response
     */
    private function sendResponseForModelNotFound(ModelNotFoundException $e)
    {
        $model = $e->getModel();
        if (method_exists($model, 'getNotFoundMessage')) {
            $message = app()->call("{$model}@getNotFoundMessage");
        } else {
            $message = trans('cms::core.messages.model_not_found');
        }

        return back()->withErrors($message, 'model_not_found');
    }

    /**
     * @param Request             $request
     * @param ValidationException $e
     *
     * @return Response
     */
    private function sendResponseForValidationError(Request $request, ValidationException $e)
    {
        return $e->getResponse();
    }
}
