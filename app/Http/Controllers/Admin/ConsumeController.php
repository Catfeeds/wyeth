<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/9/18
 * Time: 14:41
 */

namespace App\Http\Controllers\Admin;

use App\Models\Consume;
use App\Models\Course;
use App\Models\CourseCat;
use App\Models\User;
use App\Models\UserBuyCourses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConsumeController extends BaseController {
    public function index (Request $request) {
        $user_info = Session::get('admin_info');
        $params = [];
        $params['trade_id'] = $request->input('trade_id');
        $params['course_name'] = $request->input('course_name');

        $md = Consume::where('id', '>' , 0)->where('trade_status', UserBuyCourses::TRADE_PAIED);

        !empty($params['trade_id']) && $md->where("trade_id", "=", $params['trade_id']);

        if (!empty($params['course_name'])) {
            $cat_ids = CourseCat::where('name', 'like', '%' . $params['course_name'] . '%')->lists('id');
            $course_ids = Course::where('title', 'like', '%' . $params['course_name'] . '%')->lists('id');

            $md->where(function ($query) use ($cat_ids) {
                $query->where('type', UserBuyCourses::TYPE_CAT)->whereIn('cid', $cat_ids);
            })->orWhere(function ($query) use ($course_ids){
                $query->where('type', UserBuyCourses::TYPE_COURSE)->whereIn('cid', $course_ids);
            });
        }

        $per_page = 10;
        $list = $md->paginate($per_page);

        foreach ($list as $item) {
            if ($item->type == UserBuyCourses::TYPE_CAT) {
                $cat = CourseCat::find($item->cid);
                if ($cat) {
                    $item->course_name = $cat->name;
                } else {
                    $item->course_cat = '';
                }
            } else {
                $course = Course::find($item->cid);
                if ($course) {
                    $item->course_name = $course->title;
                } else {
                    $item->course_name = '';
                }
            }

            $user = User::find($item->uid);
            $item->open_id = $user->openid;
        }

        return view('admin.order.consume', ['list' => $list, 'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }
}