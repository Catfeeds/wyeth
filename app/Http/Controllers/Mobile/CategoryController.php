<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Services\CourseService;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Course;
use App\Models\Tag;
use App\Models\Banner;
use App\Models\CourseTag;
use App\Models\CourseCategory;

use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    //课程首页
    function index()
    {
        $uid = Auth::id();
        $user = Auth::user();
        $user_type = $user->type;
        $data['banner'] = Banner::where('status', 1)->orderBy('weight', 'desc')->get();
        $data['cate_list'] = Category::getCategoryList();
        $data['tags'] = Tag::select('id as tid', 'name')->orderBy('weight', 'desc')->limit(5)->get();
        $data['recommend_courses'] = CourseService::recommendCourse($uid, 3, $user_type);

        return view('mobile.category.index', $data);
    }

    //非惠氏crm用户－报名成功页面
    function search(Request $request)
    {
        if (!$request->has('key')) {
            return redirect('/mobile/category/index');
        }

        $key = $request->input('key');
        $tid = $request->input('tid');
        $caid = $request->input('caid');

        //按照关键词搜索
        if (empty($tid) && empty($caid)) {
            $courses = Course::where('title', 'like', "%$key%")->get();
        }

        //按照标签搜索
        if ($tid) {
            $cids = CourseTag::where('tid', $tid)->lists('cid');
            $courses = Course::whereIn('id', $cids)->get();
        }

        //按照类别搜索
        if ($caid) {
            $cids = CourseCategory::where('caid', $caid)->lists('cid');
            $courses = Course::whereIn('id', $cids)->get();
        }

        //按照关键词搜索
        if ($courses) {
            $uid = Auth::id();
            $courses = Course::formatCourseList($uid, $courses, false);
        }

        return view('mobile.category.search', ['courses' => $courses, 'params' => $request->all()]);
    }

}
