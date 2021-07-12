<?php

namespace App\Exceptions;

use App\Services\SimpleStateMachine\SimpleStateMachineException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Client\RequestException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }


    public function render($request, Throwable $exception)
    {
        if (config('app.env') == 'production' && $exception instanceof RequestException) {
            $response = $exception->response->json();
            $message = isset($response['message']) ? $response['message'] : 'Ошибка сервиса';
            return response()->json(['error' => true, 'message' => $message], 400);
        }

        if ($exception instanceof SimpleStateMachineException) {
            return response()->json(['message' => $exception->getMessage(), 'code' => $exception->getErrorCode()], $exception->getCode());
        }

        if (config('app.env') == 'production' && $exception instanceof ModelNotFoundException && $request->expectsJson()) {
            return response()->json(['code' => 'object_not_found', 'message' => 'Объект не найден'], 404);
        }

        if (config('app.env') == 'production' && $exception instanceof ClientException) {
            $response = $exception->getResponse();
            $content = json_decode($response->getBody()->getContents(), true);

            $message = isset($content['message']) ? $content['message'] : 'Ошибка сервиса';
            return response()->json(['error' => true, 'message' => $message], 400);
        }

        if (config('app.env') == 'production' && $exception instanceof ServerException) {
            return response()->json(['error' => true, 'message' => 'Ошибка во внутренних сервисах'], 400);
        }
        return parent::render($request, $exception);
    }
}
