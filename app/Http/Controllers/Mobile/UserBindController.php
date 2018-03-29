<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/9/26
 * Time: 下午6:27
 */

namespace App\Http\Controllers\Mobile;


use App\Http\Middleware\VerifyToken;
use App\Models\UserBind;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBindController extends Controller
{
    public function index(Request $request){
        $other_id = $request->input('other_id');
        $time = $request->input('time');
        $token = $request->input('token');
        $uid = Auth::id();

        if (!$other_id || !$time || !$token){
            $this->returnMsg('参数不合法');
        }

        if (!(new VerifyToken())->verify($request->all())){
            $this->returnMsg('token验证失败');
        }

        if ($time > time() || $time < time() - 60 * 5){
            $this->returnMsg('二维码超时,请重新生成');
        }

        $user_bind = UserBind::where('uid', $uid)->first();
        if ($user_bind){
            return redirect('/mobile/index');
        }

        $user_bind = UserBind::where('other_id', $other_id)->first();
        if ($user_bind){
            if ($user_bind->uid == $uid){
                $this->returnMsg('已绑定');
            }else{
                $this->returnMsg('已被其他人绑定');
            }
        }

        $user_bind = new UserBind();
        $user_bind->uid = $uid;
        $user_bind->other_id = $other_id;
        $user_bind->save();

        $user = Auth::user();
        $user->crm_status = 1;
        $user->save();

        $this->returnMsg('绑定成功');
    }

    private function returnMsg($msg){
        $str = <<<EOF
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>绑定账号</title>
</head>
<body>
<script>
    alert('{$msg}');
    setTimeout("location.href='/mobile/index';",500);
    
</script>
</body>
</html>
EOF;
        die($str);
    }
}