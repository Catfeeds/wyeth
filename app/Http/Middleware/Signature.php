<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

/**
 * 第三方接口签名检查
 */
class Signature
{
    public function handle($request, Closure $next)
    {
        if ($request->has('time') && $request->has('noce') && $request->has('sign')) {
            $time = $request->input('time');
            $noce = $request->input('noce');
            $sign = $request->input('sign');
            if ($time < time() - 3600 * 2) {
                return response()->json(['status' => 0, 'error_msg' => 'signature has expired', 'data' => []]);
            }

            //签名数组
            $signature_keys = config('course.signature_keys');
            $flag = false;
            if ($signature_keys) {
                foreach ($signature_keys as $key) {
                    if ($sign == substr(md5(md5($key) . $key . $time . $noce), 7, 18)) {
                        $flag = true;
                        Session::put('channel', $key);
                        break;
                    }
                }
            }

            if(!$flag){
                return response()->json(['status' => 0, 'error_msg' => 'invalid signature', 'data' => []]);
            }else{
                return $next($request);
            }
        }

        return response()->json(['status' => 0, 'error_msg' => 'invalid params', 'data' => []]);
    }

}