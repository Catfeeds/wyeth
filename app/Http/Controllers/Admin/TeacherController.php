<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Cache;
use DB;
use Endroid\QrCode\QrCode;
use GuzzleHttp;
use Illuminate\Http\Request;
use JWTAuth;
use JWTFactory;
use Redirect;
use Session;
use App\Models\User;
use App\Models\Course;
use App\Models\Courseware;

class TeacherController extends Controller
{
    private $cidArr = [];

    public function __construct()
    {
        $anchorInfo = Session::get('teacher_info');
        $this->cidArr = $anchorInfo->cids;
    }

    public function index(Request $request)
    {
        $courses = DB::table('course')->whereIn("id", $this->cidArr)->get();
        $course_enroll = $course_start = $course_end = [];
        if (!empty($courses)) {
            foreach ($courses as $key => $val) {
                if ($val->status == 1) {
                    $course_enroll[] = $val;
                } else if ($val->status == 2) {
                    $course_start[] = $val;
                } else {
                    $course_end[] = $val;
                }
            }
            $course_info = $courses[0];
        } else {
            $course_info = new \stdClass();
            $course_info->teacher_avatar = '';
            $course_info->teacher_name = '';
        }
        $anchor_info = new \stdClass();
        $anchor_info->avatar = 'http://7xk3aj.com1.z0.glb.clouddn.com/FlN9bfkk4Nnsmu3azPLYJeEkFtI4';
        $anchor_info->name = 'Miss惠';
        $data = [
            'anchor_info' => $anchor_info,
            'course_info' => $course_info,
            'course_enroll' => $course_enroll,
            'course_start' => $course_start,
            'course_end' => $course_end,
        ];
        return view('admin.teacher.index', $data);
    }

    public function live(Request $request, $cid)
    {
        if (!in_array($cid, $this->cidArr)) {
            die("<script>alert('账号没有该课程'); location.href='/admin/teacher';</script>");
        }
        $course = Course::where('id', $cid)->first();
        // $user = User::where('id', $course->anchor_uid)->first();
        // if (empty($user)) {
        //     die("<script>alert('该课程没有绑定讲师'); location.href='/admin/anchor';</script>");
        // }
        $payload = JWTFactory::make([
            'user_type' => User::TYPE_TEACHER,
            'uid' => User::CHAT_UID_TEACHER,
            'nickname' => 'Miss惠讲师'
        ]);
        Session::put('token', JWTAuth::encode($payload));

        $anchor_info = new \stdClass();
        $anchor_info->avatar = 'http://7xk3aj.com1.z0.glb.clouddn.com/FlN9bfkk4Nnsmu3azPLYJeEkFtI4';
        $anchor_info->name = 'Miss惠';
        $anchor_info->anchor_uid = User::CHAT_UID_ANCHOR;
        $ware = Courseware::where('cid', $cid)->get()->toArray();
        $data = [
            'course_info' => $course,
            'teacher_uid' => User::CHAT_UID_TEACHER,
            'anchor_uid' => User::CHAT_UID_ANCHOR,
            'anchor_info' => $anchor_info,
            'hls_record_addr' => config('course.aodianyun_addr'),
            'hls_record_stream' => config('course.aodianyun_stream_pre') . $cid,
            'ware' => $ware,
            'debug' => $request->input('debug', 0)
        ];
        return view('admin.teacher.live', $data);
    }
}
