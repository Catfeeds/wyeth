<?php namespace App\Http\Middleware;

use Closure;
use App\Models\Course;
use App\Models\UserCourse;
use Auth;

class SignCourse
{
    //查询用户是否报名课程
    public function handle($request, Closure $next)
    {
        $cid = $request->input('cid');
        $user = Auth::user();
        $uid = Auth::id();
        $user_course = UserCourse::where(['cid' => $cid, 'uid' => $uid])->first();

        //check course
        $course = Course::where('id', $cid)->first();
        if (empty($course)) {
            return Redirect('/mobile/index');
        }

        //check user
        if (in_array($uid, [$course->anchor_uid, $course->teacher_uid])) {
            return $next($request);
        }

        //check user sign
        if ($user->crm_status && $user_course) {
            return $next($request);
        }

        return Redirect('/mobile/reg?cid=' . $cid);
    }

}