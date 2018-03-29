<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Session;
use View;

class TeacherController extends Controller
{
    public function __construct()
    {
        // openid token 指到所有的模板中
        $this->openid = Session::get('openid');
        $this->token = Session::get('token');
        View::share('openid', $this->openid);
        View::share('token', $this->token);
    }

    public function index(Request $request)
    {
        $uid = $request->input('uid');
        $cid = $request->input('cid');
        $teacher = Course::where('id', $cid)->where('teacher_uid', $uid)->first();
        if ($teacher) {
            $data = [
                'cid' => $cid,
                'uid' => $uid,
                'name' => $teacher->teacher_name,
                'avatar' => $teacher->teacher_avatar,
                'user_type' => \App\Models\User::TYPE_TEACHER,
                'status' => $teacher->status,
            ];
            return view('mobile.teacher.index', $data);
        }
    }
}
