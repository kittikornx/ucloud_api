<?php

namespace App\Http\Middleware;

use App\Member;
use App\UserToken;
use Closure;

class ApiToken
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
        $userToken = UserToken::where('token', '=', $request->header('Authorization'))->limit(1)->first();

        if (!$userToken) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        // // Check Token expired
        // if (Carbon::now()->diffInSeconds($userToken->expire_at, false) <= 0) {
        //     Log::debug('ApiTokenMiddleware', [
        //         'time_now' => Carbon::now()->toDateTimeString(),
        //         'expire_at' => $userToken->expire_at
        //     ]);
        //     return response()->json(['success' => false, 'message' => 'Token expired.'], 401);
        // }

        app()->singleton(Member::class, function () use ($userToken) {
            return Member::where('m_id','=',$userToken->m_id)->first();
        });

        return $next($request);
    }
}
