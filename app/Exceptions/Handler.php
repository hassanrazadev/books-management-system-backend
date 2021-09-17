<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler {
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
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register() {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e) {
        if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            // if token expires then redirect to login page
            return redirect()
                ->route('customer.login');

        }

        if ($request->wantsJson() || $request->ajax()) {
            return $this->handleApiException($request, $e);
        } else {
            return parent::render($request, $e);
        }
    }

    /**
     * @param $request
     * @param $exception
     * @return JsonResponse
     */
    private function handleApiException($request, $exception): JsonResponse {
        $exception = $this->prepareException($exception);

        if ($exception instanceof \HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception);
    }

    /**
     * @param $exception
     * @return JsonResponse
     */
    private function customApiResponse($exception): JsonResponse {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        $response = [
            'status' => false,
            'data' => []
        ];

        switch ($statusCode) {
            case 401:
                $response['message'] = 'Unauthorized';
                break;
            case 403:
                $response['message'] = 'Forbidden';
                break;
            case 404:
                $response['message'] = 'Record Not Found';
                break;
            case 405:
                $response['message'] = 'Method Not Allowed';
                break;
            case 422:
                $response['message'] = reset($exception->original['errors'])[0] ?? 'The given data was invalid';
                $response['data']['errors'] = $exception->original['errors'];
                break;
            default:
                $response['message'] = $exception->getMessage();
                break;
        }

        $response['code'] = $statusCode;

        return response()->json($response, $statusCode);
    }
}
