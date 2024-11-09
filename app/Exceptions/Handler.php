<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
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
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                $classException = get_class($e);
                $response = null;
                $lang = null;
                if ($request->lang) {
                    $lang = $request->lang;
                };
                switch ($classException) {
                    case NotFoundHttpException::class:
                        $response =  printJson(null, buildStatusObject('PAGE_NOT_FOUND'), $lang);
                        break;
                    case MethodNotAllowedHttpException::class:
                        $response = printJson(null, buildStatusObject('METHOD_NOT_ALLOWED'), $lang);
                        break;
                    case AccessDeniedHttpException::class:
                        $response = printJson(null, buildStatusObject('FORBIDDEN'), $lang);
                        break;
                    case UnauthorizedHttpException::class:
                        $response = printJson(null, buildStatusObject('UNAUTHORIZED'), $lang);
                        break;
                    case ServiceUnavailableHttpException::class:
                        $response = printJson(null, buildStatusObject('SERVICE_UNAVAILABLE'), $lang);
                        break;
                    default:
                        $response = parent::render($request, $e);
                }
                return $response;
            }
        });
    }
}
