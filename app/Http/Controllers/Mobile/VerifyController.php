<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Redirect, Input;
use Illuminate\Support\Facades\Crypt;
use App\Services\WxWyeth;
use View;

class VerifyController extends Controller
{
    function __construct()
    {
        //微信jssdk参数
        $wxWyeth = new WxWyeth();
        $this->package = $wxWyeth->getSignPackage();
        View::share('package', $this->package);
    }

    function teacher(Request $request, $hash)
    {
        $gets = $request->all();
        $cid = Crypt::decrypt($hash);

        $course_info = DB::table('course')->select(['id','title','start_day','start_time','end_time', 'teacher_uid', 'teacher_name'])->where('id',$cid)->first();
        if (empty($course_info)) {
            return view('mobile.verify.error',['msg'=>'课程不存在','url'=>'/mobile/index']);
        }

        $openid = Session::get('openid');
        $user_info = DB::table("user")->where('openid', $openid)->first();

        if (empty($user_info)) {
            return view('mobile.verify.error',['msg'=>'登录失败，请重试','url'=>'']);
        }

        if ($course_info->teacher_uid > 0) {
            return view('mobile.verify.error',['msg'=>'该课程已绑定过，正在进入..','url'=>'/mobile/teacher/index?cid='.$cid.'&uid='.$user_info->id]);
        }

        if (isset($gets['confirm']) && $gets['confirm'] == 'yes') {
            DB::table('course')->where('id',$cid)->update(array('teacher_uid' => $user_info->id));
            //return view('mobile.verify.error',['msg'=>'恭喜您，绑定成功','url'=>'/mobile/teacher/index?cid='.$cid.'&uid='.$user_info->id]);
            return view('mobile.verify.error',['msg'=>'恭喜您，绑定成功','url'=>'close']);
        }

        return view('mobile.verify.teacher',['course_info'=>$course_info]);
    }

    function anchor(Request $request, $hash)
    {
        $gets = $request->all();
        $cid = Crypt::decrypt($hash);

        $course_info = DB::table('course')->select(['id','title','start_day','start_time','end_time', 'anchor_uid'])->where('id',$cid)->first();
        if (empty($course_info)) {
            return view('mobile.verify.error',['msg'=>'课程不存在','url'=>'/mobile/index']);
        }

        $openid = Session::get('openid');
        $user_info = DB::table("user")->where('openid', $openid)->first();

        if (empty($user_info)) {
            return view('mobile.verify.error',['msg'=>'登录失败，请重试','url'=>'']);
        }

        if ($course_info->anchor_uid > 0) {
            return view('mobile.verify.error',['msg'=>'该课程已绑定过，正在进入..','url'=>'/mobile/index']);
        }

        if (isset($gets['confirm']) && $gets['confirm'] == 'yes') {
            DB::table('course')->where('id',$cid)->update(array('anchor_uid' => $user_info->id));
            //return view('mobile.verify.error',['msg'=>'恭喜您，绑定成功','url'=>'/mobile/index']);
            return view('mobile.verify.error',['msg'=>'恭喜您，绑定成功','url'=>'close']);
        }

        return view('mobile.verify.anchor',['course_info'=>$course_info]);
    }
}
