<?php
namespace Nemutagk\BpBInsumos\Handler;

use Log;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\{HttpException,NotFoundHttpException,MethodNotAllowedHttpException};
use Illuminate\Database\QueryException;
use Nemutagk\BpBInsumos\Exception\HttpErrorException as BpBHttpErrorException;

class ExceptionHandler
{
	public static function response($request, Throwable $exception) {
		$response = [
            'success'=>false
            ,'message' => $exception->getMessage()
        ];

        if (!$exception instanceof BpBHttpErrorException)
            $code = $exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException || $exception instanceof MethodNotAllowedHttpException ? 404 : 500;
        else {
            $code = $exception->getHttpCode();
            $response['error'] = $exception->getError();

            if (!empty($exception->getResponse()))
                $response['response'] = $exception->getResponse();
        }

        $response['request_code'] = str_rand(8, false);

        if ($exception instanceof ValidationException) {
            $code = 400;
            $response['message'] = 'Error en la validación';
            $response['error'] = $exception->errors();
        } else if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException || $exception instanceof MethodNotAllowedHttpException) {
            $response['message'] = 'No se encontró el recurso solicitado';
            $response['route'] = $request->fullUrl();
            $response['method'] = $request->method();

            if ($exception->getMessage())
                $response['error'] = $exception->getMessage();
        }

        $response['context'] = [
            'file' => $exception->getFile()
            ,'line' => $exception->getLine()
            ,'trace' => explode("#", $exception->getTraceAsString())
            ,'content' => (string)$exception
        ];

        Log::error('Exception: ', $response);

        if (env('APP_ENV') == 'production') {
            unset($response['context']);

            if (isset($response['route']))
                unset($response['route']);

            if (isset($response['method']))
                unset($response['method']);

            if ($exception instanceof QueryException)
                $response['message'] = 'Favor de comunicarse con el administrador, código de error: '.$response['code'];
        }

        return response($response, $code);
	}
}