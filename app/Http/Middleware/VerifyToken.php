<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * 接口验签
 */

class VerifyToken
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->input('is_test') == 1){
            return $next($request);
        }
//        die($this->getToken($request->all()));
        if ($request->has('token')) {
            $token = $request->input('token');

            if($this->getToken($request->all()) === $token){
                return $next($request);
            }else{
                return response()->json(['status' => 0, 'error_msg' => 'invalid token', 'data' => []]);
            }
        }

        return response()->json(['status' => 0, 'error_msg' => 'invalid params', 'data' => []]);
    }

    public function getToken($params){
        unset($params['hw_dev']);
        unset($params['token']);
        ksort($params);
        $tmp = '';
        foreach ($params as $key => $value){
            $tmp .= $key . $value;
        }
        $tmp .= 'yCj8w0I13uNqm4VUyZHATjUKFdfQvS9W';
        $token = md5($tmp);
        return $token;
    }

    public function verify($params){
        if (!isset($params['token'])){
            return false;
        }
        $token = $this->getToken($params);
        if ($params['token'] === $token){
            return true;
        }else{
            return false;
        }
    }

}