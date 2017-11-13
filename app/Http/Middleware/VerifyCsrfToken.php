<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;
use Closure;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];


    /**
     * Handle an incoming request.  Overloads handle() of Illuminate\Foundation\Http\Middleware\VerifyCsrfToken
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     */
    public function handle($request, Closure $next)
    {

        try {

            return parent::handle($request, $next);

        } catch (TokenMismatchException $e) {

            if ($request->ajax()) {

                return response()->json(['message' => [__('Token is not valid or the session time has expired. Login again.')]], 401);

            } else {

                return redirect()->route('login');
            }


        }
    }
}
