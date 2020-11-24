<?php
namespace Nemutagk\BpBInsumos\Handler;

use Log;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\{HttpException,NotFoundHttpException,MethodNotAllowedHttpException};

class ExceptionHandler
{
	public static function response($request, Throwable $exception) {
		$response = [
            'success'=>false
            ,'message' => $exception->getMessage()
        ];

        // Log::info('Exception instance: '.get_class($exception));

        $code = $exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException || $exception instanceof MethodNotAllowedHttpException ? 404 : 500;

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
        }

        return response($response, $code);
	}
}