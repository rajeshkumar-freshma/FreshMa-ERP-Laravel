<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use ErrorException;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
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

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $isApi = $request->expectsJson() || ($request->route() && collect($request->route()->middleware())->contains('api'));
        if (!$isApi) {
            return redirect()->guest(route('admin.login'));
        }

        return response()->json([
            'status' => 401,
            'message' => 'Unauthenticated',
        ], 401);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if ($this->shouldNotifyTelegram($e)) {
                $message = app()->environment('production')
                    ? class_basename($e) . ': ' . $e->getMessage()
                    : (string) $e;
                $this->sendTelegramMessage($message);
            }

        });
    }

    public function render($request, Throwable $exception)
    {
        $isApi = $request->expectsJson() || ($request->route() && collect($request->route()->middleware())->contains('api'));
        if ($isApi) {
            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            if ($exception instanceof AuthorizationException) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Forbidden',
                ], 403);
            }

            if ($exception instanceof ValidationException) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Validation failed',
                    'errors' => $exception->errors(),
                ], 422);
            }

            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Resource not found',
                ], 404);
            }

            // Convert payload/data-shape runtime issues to client errors for API callers.
            if (
                $exception instanceof QueryException ||
                $exception instanceof ErrorException ||
                $exception instanceof \TypeError
            ) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Invalid request payload',
                ], 422);
            }

            $status = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;
            $message = $status >= 500
                ? 'Server error'
                : ($exception->getMessage() ?: 'Request failed');

            return response()->json([
                'status' => $status,
                'message' => $message,
            ], $status);
        }

        return parent::render($request, $exception);
    }

    private function shouldNotifyTelegram(Throwable $e): bool
    {
        if (!filter_var(env('TELEGRAM_EXCEPTION_ALERTS', false), FILTER_VALIDATE_BOOLEAN)) {
            return false;
        }

        if (app()->environment('local', 'testing')) {
            return false;
        }

        if ($e instanceof ValidationException || $e instanceof AuthenticationException || $e instanceof NotFoundHttpException) {
            return false;
        }

        return true;
    }

    private function sendTelegramMessage($message)
    {
        try {
            Telegram::bot(config('telegram.default', 'mybot'))->sendMessage([
                'chat_id' => env('TELEGRAM_CHAT_ID'),
                'text' => $message,
            ]);
        } catch (\Exception $e) {
            // Log or handle the error
            Log::error('Telegram message sending failed: ' . $e->getMessage());
        }
    }

    // public function render($request, Throwable $exception)
    // {
    //     if ($exception instanceof NotFoundHttpException) {
    //         // Handle 404 error, redirect to error page
    //         return response()->view('errors.404', [], 404);
    //     }

    //     return parent::render($request, $exception);
    // }
}
