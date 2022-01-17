<?php

namespace App\Exceptions;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
        //
    }

    public function render($request, Throwable $exception)
    {
        if($this->isHttpException($exception)) {
            if($exception->getStatusCode() == 403) {
                if (Auth::guest()) {
                    return response()->view('errors.403');
                } else {
                    if (auth()->user()->hasAnyRole(Role::get())) {
                        return response()->view('errors.auth.403');
                    } else {
                        return response()->view('errors.403');
                    }
                }
            }

             if($exception->getStatusCode() == 404) {
                if (Auth::guest()) {
                    return response()->view('errors.404');
                } else {
                    if (auth()->user()->hasAnyRole(Role::get())) {
                        return response()->view('errors.auth.404');
                    } else {
                        return response()->view('errors.404');
                    }
                }
            }
        }

        return parent::render($request, $exception);
    }
}
