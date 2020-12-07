<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    { 
         if ($exception instanceof ModelNotFoundException &&
        $request->wantsJson()) {
             return response()->json([
              'error'=>'Not Found Resource'
             ],404);
         }
          if ($exception instanceof Exception &&
        $request->wantsJson()){
             return response()->json([
              'error'=>'Have a error occur'
             ],500);
         }
        if ($exception instanceof RequestException &&
        $request->wantsJson()){
             return response()->json([
              'error'=>'Request was wrong.Please try again'
             ],400);
         }
         if ($exception instanceof AuthenticationException &&
        $request->wantsJson() ){
             return response()->json([
              'error'=>'UnAuthentication'
             ],401);
         }
          if ($exception instanceof HttpException &&
        $request->wantsJson() ){
             return response()->json([
              'error'=>'Http error'
             ],400);
         }
          if ($exception instanceof ValidationException &&
        $request->wantsJson() ){
             return response()->json([
              'error'=>'Format wrong'
             ],500);
         }
        return parent::render($request, $exception);
    }
}
