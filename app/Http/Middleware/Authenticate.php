<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
      protected function redirectTo($request)
    {
        // If it's an API/fetch request return null (will give 401 JSON response)
        // If it's a normal page visit redirect to login
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
