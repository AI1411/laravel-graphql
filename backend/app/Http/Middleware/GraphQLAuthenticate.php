<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate;

class GraphQLAuthenticate extends Authenticate
{
    public function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return false;
        }
    }
}
