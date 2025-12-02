<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        $error_log = "server_error.log";
        make_error_log($error_log, "=================start================");
        
        $log_message = [
            'message' => $exception->getMessage(),
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'input' => request()->all(),
        ];
        make_error_log($error_log, print_r($log_message,1));

        // 419エラー（TokenMismatchException）トークンが無効
        if ($exception instanceof TokenMismatchException) {
            $message = ['message' => 'セッションの有効期限が切れました。もう一度ログインしてください。',
                        'type' => 'error',
                        'sec' => '2000'];
            return redirect()->route('login')->with($message);
        };

        //postメソッドをgetで呼び出した場合
        if ($exception instanceof MethodNotAllowedHttpException) {
            make_error_log($error_log, "Method does not match  =" . print_r($log_message,1));
            $message = ['message' => 'このページはPOSTメソッドのみサポートしています。', 
                        'type' => 'error',
                        'sec' => '2000'];
            return redirect()->route('home')->with($message);
        }
        
        //404エラー　対処のページがない
        if ($exception instanceof NotFoundHttpException) {
            make_error_log($error_log, "[404]error not_page  =" . print_r($log_message,1));
            $message = ['message' => 'ページが見つかりませんでした。',
                        'type' => 'error',
                        'sec' => '2000'];
            return redirect()->route('home')->with($message);
        }

        // 500エラー（Internal Server Error）のハンドリング
        if ($exception instanceof HttpException && $exception->getStatusCode() === 500) {
            make_error_log($error_log, "[500]error  =" . print_r($log_message,1));
            $message = [
                        'message' => '内部サーバーエラーが発生しました。',
                        'type' => 'error',
                        'sec' => '2000'
            ];
            return redirect()->route('home')->with($message);
        }


        return parent::render($request, $exception);
    }
}
