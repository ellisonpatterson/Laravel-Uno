<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $allowRedirect = !($request->ajax() || $request->wantsJson());

        // if ($request->ajax() || $request->wantsJson()) {
            // if ($exception instanceof ValidationException) {
                // $messageType = 'alert';
                // foreach ($exception->errors() as $error) {
                    // $message[] = $error;
                // }
            // } else {
                // list($message, $messageType) = app()->make('helper')->fetchSessionMessages();
                // if (empty($message) && empty($messageType)) {
                    // $message = $exception->getMessage();
                    // if (is_object($message)) {
                        // $message = $message->toArray();
                    // }
                // }
            // }

            // return new JsonResponse([
                // 'message' => $message,
                // 'type' => $messageType ?: 'danger'
            // ], 422);
        // }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        return $request->expectsJson()
                    ? response()->json(['message' => $exception->getMessage()], 401)
                    : redirect()->guest($this->getRedirectUrl($request));
    }

    protected function getRedirectUrl($request)
    {
        $referer = $request->header('referer');
        if (empty($referer)) {
            return url('/');
        } else {
            return app('url')->previous();
        }
    }
}
