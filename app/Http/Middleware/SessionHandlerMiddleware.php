<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SessionHandlerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $bearer= $request->header('Authorization');
        $currentToken = explode(' ', $bearer)[1];
        
        $data = auth()->user()->sessions->pluck('token')->filter(function($token)use($currentToken){
            return $currentToken == $token;
        });

        if($data->isEmpty()){
            return abort(401);
        }

        return $next($request);
    }
}
