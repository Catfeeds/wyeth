<?php

namespace App\Http\Middleware;

use JWTAuth;
use Tymon\JWTAuth\Token;
use Tymon\JWTAuth\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Models\User;
use Auth;
use Session;


/**
 * JWTAuth 实现的获取用户信息
 */
class GetUserFromToken extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        try {
            $token = JWTAuth::getToken();
            if (! $token) {
                $token_request = $request->input('token');
                if (!$token_request) {
                    return $this->respond('tymon.jwt.absent', 'token_not_provided'. $request->input('token'), 400);
                }
                $token = new Token($token_request);
                if (!$token) {
                    return $this->respond('tymon.jwt.absent', 'token_not_provided'. $request->input('token'), 400);
                }
            }

            $payload = JWTAuth::decode($token);
            Session::put('user_type', $payload->get('user_type'));
            $uid = $payload->get('uid');
            if (!$uid) {
                return $this->respond('tymon.jwt.absent', 'uid_not_found_in_token', 404);
            }
            if(in_array($uid, [User::CHAT_UID_ANCHOR, User::CHAT_UID_TEACHER])) {
                $user = new User();
                $user->id = $uid;
                $user->name = 'Miss惠';
            } else {
                $user = User::find($uid);
            }

        } catch (TokenExpiredException $e) {
            return $this->respond('tymon.jwt.expired', 'token_expired', $e->getStatusCode(), [$e]);
        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
        }

        if (! $user) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }

        Auth::setUser($user);
        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }
}
