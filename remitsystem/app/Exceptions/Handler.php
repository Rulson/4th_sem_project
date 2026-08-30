<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [];

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if($request->is('api*')){
            if($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException){
                dd($exception);
                return response()->json(['message' => ($exception->getMessage()) ? $exception->getMessage()  : 'Not found.' , 'status' => $exception->getStatusCode()]);
            }
        }

        if ($exception instanceof TokenMismatchException) {

            return redirect(route('login'))->with('message', 'You page session has expired. Please login again');
        }

        return parent::render($request, $exception);
    }
}
