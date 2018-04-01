<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Log;

class AuthJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            Log::info(JWTAuth::getToken());
            $user = JWTAuth::toUser(JWTAuth::getToken());

            $request->attributes->add([
                'user' => $user,
                'to_chuc' => $user->toChuc
            ]);

        } catch (\Exception $e) {
            if ($e instanceof TokenInvalidException){
                return response()->json(['error'=>'Token is Invalid']);
            }else if ($e instanceof TokenExpiredException){
                return response()->json(['error'=>'Token is Expired']);
            }else{
                return response()->json(['error'=> $e->getMessage()]);
            }
        }
        return $next($request);
    }
}
