<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class Authenticate extends Middleware
{

  
        protected function unauthenticated($request, array $guards)
        {
            if ($request->expectsJson()) {
                throw new HttpResponseException(
                    response()->json(['message' => 'Please login first'], 401)
                );
            }
         
    
            // If it's not an API request, call the parent method to handle redirection
            parent::unauthenticated($request, $guards);
        }
    
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
   
}
