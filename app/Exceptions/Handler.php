<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;
use Throwable;
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
        if (!($request->expectsJson() || collect($request->route()->middleware())->contains('api'))) {
            return redirect()->guest(route('admin.login'));
        }

        abort(
            response()->json(
                [
                    'status' => '401',
                    'message' => 'Unauthenticated',
                ],
                401
            )
        );
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
            if ($e instanceof \Exception) {
                $message = $e->getMessage();
                $this->sendTelegramMessage($message);
            }

        });
    }

    private function sendTelegramMessage($message)
    {
        try {
            Telegram::bot('mybot')->sendMessage([
                'chat_id' => -1002130051998,
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
