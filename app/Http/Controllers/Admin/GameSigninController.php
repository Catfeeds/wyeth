<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Admin;
use App\Models\SigninItem;
use App\Models\SigninGameConfig;
use App\Models\SigninWinRecords;
use App\Models\AreaCity;
use App\Models\Course;
use App\Services\Qnupload;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session; // cat
use Excel;

class GameSigninController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $user_info = Session::get('admin_info');
        //获取游戏列表
        $query = SigninGameConfig::orderBy('id','asc');
        if (!empty($params['cid'])) {
            $query->where('cid', '=', $params['cid']);
        }
        $query = $query->paginate(10);
        return view('admin.signin_game.index', ['list' => $query,'query' => $query, 'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function signList(Request $request, $id = 0)
    {
        $params = $request->all();
        $user_info = Session::get('admin_info');
        //获取游戏配置信息
        $signinGameConfig = SigninGameConfig::where('cid', '=', $id);
        if ($signinGameConfig->count() != 0) {
            $configInfo = $signinGameConfig->first();
        } else {
            $configInfo = array();
        }

        //当前课程游戏列表分页
        $per_page = 10;
        $params = $request->all();
        $query = DB::table('signin_items')->select('signin_items.*', 'user.openid', 'user.nickname')->where('cid', '=', $id);
        $query->join('user', 'user.id', '=', 'signin_items.start_uid');

        if (!empty($params['nickname'])) {
            $query->where('user.nickname', 'like', '%'.$params['nickname'].'%');
        }

        $query = $query->orderBy('signin_num', 'desc')->paginate($per_page);
        foreach ($query as $key=> $value) {
            $signin_id = $value->id;
            $query[$key]->order = $this->getSignOrderById($signin_id);
        }

        $orderList = $query->sortBy('order');
        $win_num = !empty($configInfo->win_num) ? $configInfo->win_num : 10;
        return view('admin.signin_game.signin_list', ['list' => $orderList, 'params' => $params, 'win_num' => $win_num, 'query' =>  $query, 'sign_config' => $configInfo])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    /**
     * @param Request $request
     * @param int $type 编辑类型 0 新建  1 修改
     * @param int $id
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function config(Request $request, $type = 0, $id = 0)
    {
        $user_info = Session::get('admin_info');
        $id = intval($id);
        if ($id) {
            $info = Course::where("id", $id)->first();
            if (empty($info)) {
                return view('admin.error', ['msg' => '数据不存在']);
            }
        } else {
            $info = new Course();
        }
        //获取游戏配置信息
        $data['sign_config'] = $signinGameConfig = SigninGameConfig::where('cid', '=', $id);
        if ($signinGameConfig->count() != 0) {
            $configInfo = $signinGameConfig->first();
        } else {
            $configInfo = array();
        }
        //获取所有未开启游戏的课程
        $array = SigninGameConfig::select('cid')->get()->toArray();
        $idArr = [];
        for ($i = 0; $i < count($array); $i++) {
            $idArr[$i] = $array[$i]['cid'];
        }
        $courses = Course::where('signin_status', '=', 0)->whereNotIn('id', $idArr)->get()->toArray();
        //获取最近一堂游戏的配置
        return view('admin.course.signin_edit', ['info' => $info, 'id' => $id, 'sign_config' => $configInfo, 'courses' => $courses, 'type' => $type])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @description 修改课程游戏开启状态
     */
    public function signSwitch (Request $request)
    {
        $courseId = $request->input('courseId');
        $signStatus = $request->input('signStatus');
        $course = Course::where('id', '=', $courseId);
        if ($course->count() == 0) {
            $result = ['status' => 500, 'code' => '课程不存在'];
            return response()->json($result);
            die();
        }
        $courseInfo = $course->first();
        if ($courseInfo->signin_status == $signStatus) {
            $result = ['status' => 500, 'code' => '游戏已开启或关闭成功,请勿重复修改'];
            return response()->json($result);
            die();
        }
        //判断当前课程是否开启过游戏
        $signinItem = SigninItem::where('cid', '=', $courseId);
        if ($signinItem->count() > 0 && $signStatus == 1) {
            $result = ['status' => 500, 'code' => '当前课程已创建过游戏,不能重复创建!'];
            return response()->json($result);
            die();
        }
        //判断是否存在游戏
        $signinGameConfigRows = SigninGameConfig::where('cid', '=', $courseId)->count();
        if ($signinGameConfigRows == 0 && $signStatus == 1) {
            //新建一条默认游戏
            $signinGameConfig = new SigninGameConfig();
            $signinGameConfig->cid = $courseId;
            $signinGameConfig->win_num = 10;
            $signinGameConfig->fri_share_title = '你懒你先睡,我美我拿奖！';
            $signinGameConfig->fri_share_desc = '我正在听妈妈微课堂直播，一起来签到，马上赢奶粉！';
            $signinGameConfig->fri_circle_share_title = '我正在听妈妈微课堂直播，一起来签到，马上赢奶粉！';
            $signinGameConfig->save();
        }
        $updateCourse = Course::find($courseId);
        $updateCourse->signin_status = $signStatus;
        $updateCourse->save();
        if ($updateCourse) {
            $result = ['status' => 200, 'code' => '游戏已开启或关闭成功'];
        } else {
            $result = ['status' => 500, 'code' => '游戏已开启或关闭失败'];
        }
        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return
     * @descript 保存游戏信息配置
     */
    public function saveSignConfig (Request $request)
    {
        if ($request->has('cid')) {
            //判断是否存在当前这节课程
            $course = Course::where('id', '=', $request->input('cid'));
            if ($course->count() == 0) {
                return view('admin.error', ['msg' => '课程不存在']);
            }
        } else {
            //返回错误信息
            return view('admin.error', ['msg' => '传递参数有误']);
        }
        if (empty($request->input('win_num')) || empty($request->input('fri_share_title')) || empty($request->input('fri_share_desc')) || empty($request->input('circle_share_desc'))) {
            return view('admin.error', ['msg' => '请将信息填写完整']);
        }

        $data = [];
        if (!empty($_FILES['sign_share_picture']) && $_FILES['sign_share_picture']['size'] > 0) {
            $data['sign_share_picture'] = Qnupload::upload($_FILES['sign_share_picture'], null, 'signin/share');
        }
        if (!empty($_FILES['brand_img']) && $_FILES['brand_img']['size'] > 0) {
            $data['brand_img'] = Qnupload::upload($_FILES['brand_img'], null, 'signin/brand');
        }
        if (!empty($_FILES['rule_img']) && $_FILES['rule_img']['size'] > 0) {
            $data['rule_img'] = Qnupload::upload($_FILES['rule_img'], null, 'signin/rule');
        }
        if (!empty($_FILES['intro_img']) && $_FILES['intro_img']['size'] > 0) {
            $data['intro_img'] = Qnupload::upload($_FILES['intro_img'], null, 'signin/intro');
        }
        if (!empty($_FILES['teacher_img']) && $_FILES['teacher_img']['size'] > 0) {
            $data['teacher_img'] = Qnupload::upload($_FILES['teacher_img'], null, 'signin/teacher');
        }
        if (!empty($_FILES['living_img']) && $_FILES['living_img']['size'] > 0) {
            $data['living_img'] = Qnupload::upload($_FILES['living_img'], null, 'signin/living');
        }
        if (!empty($_FILES['prize_img']) && $_FILES['prize_img']['size'] > 0) {
            $data['prize_img'] = Qnupload::upload($_FILES['prize_img'], null, 'signin/prize');
        }
        if (!empty($_FILES['award_img']) && $_FILES['award_img']['size'] > 0) {
            $data['award_img'] = Qnupload::upload($_FILES['award_img'], null, 'signin/award');
        }
        if (!empty($_FILES['user_info_title']) && $_FILES['user_info_title']['size'] > 0) {
            $data['user_info_title'] = Qnupload::upload($_FILES['user_info_title'], null, 'signin/user_info');
        }
        //判断当前课程是否创建过游戏配置记录
        $signinGameConfigCheck = SigninGameConfig::where('cid', '=', $request->input('cid'))->first();
        if (!$signinGameConfigCheck) {
            $signinGameConfig = new SigninGameConfig();
        } else {
            $signinGameConfig = SigninGameConfig::find($signinGameConfigCheck->id);
        }

        $signinGameConfig->cid = $request->input('cid');
        $signinGameConfig->win_num = $request->input('win_num');
        $signinGameConfig->fri_share_title = $request->input('fri_share_title');
        $signinGameConfig->fri_share_desc = $request->input('fri_share_desc');
        $signinGameConfig->fri_circle_share_title = $request->input('circle_share_desc');
        if (isset($data['sign_share_picture'])) {
            $signinGameConfig->share_img = $data['sign_share_picture'];
        }

        if (isset($data['brand_img'])) {
            $signinGameConfig->brand_img = $data['brand_img'];
        }

        if (isset($data['rule_img'])) {
            $signinGameConfig->rule_img = $data['rule_img'];
        }

        if (isset($data['intro_img'])) {
            $signinGameConfig->intro_img = $data['intro_img'];
        }

        if (isset($data['teacher_img'])) {
            $signinGameConfig->teacher_img = $data['teacher_img'];
        }

        if (isset($data['living_img'])) {
            $signinGameConfig->living_img = $data['living_img'];
        }

        if (isset($data['prize_img'])) {
            $signinGameConfig->prize_img = $data['prize_img'];
        }

        if (isset($data['award_img'])) {
            $signinGameConfig->award_img = $data['award_img'];
        }

        if (isset($data['user_info_title'])) {
            $signinGameConfig->user_info_title = $data['user_info_title'];
        }

        $signinGameConfig->save();
        if ($signinGameConfig) {
            return view('admin.error', ['msg' => '游戏配置更新成功', 'url' => '/admin/signin/index']);
        } else {
            return view('admin.error', ['msg' => '已更新', 'url' => '/admin/course/signin/1/'.$request->input('cid')]);
        }

    }

    /**
     * @description 新建游戏配置
     */
    public function insertSignConfig (Request $request) {
        if (count($request->input('cids')) == 0) {
            return view('admin.error', ['msg' => '请选择关联课程']);
        }
        if (empty($request->input('win_num')) || empty($request->input('fri_share_title')) || empty($request->input('fri_share_desc')) || empty($request->input('circle_share_desc'))) {
            return view('admin.error', ['msg' => '请将信息填写完整']);
        }

        $data = [];
        if (!empty($_FILES['sign_share_picture']) && $_FILES['sign_share_picture']['size'] > 0) {
            $data['sign_share_picture'] = Qnupload::upload($_FILES['sign_share_picture'], null, 'signin/share');
        }
        if (!empty($_FILES['brand_img']) && $_FILES['brand_img']['size'] > 0) {
            $data['brand_img'] = Qnupload::upload($_FILES['brand_img'], null, 'signin/brand');
        }
        if (!empty($_FILES['rule_img']) && $_FILES['rule_img']['size'] > 0) {
            $data['rule_img'] = Qnupload::upload($_FILES['rule_img'], null, 'signin/rule');
        }
        if (!empty($_FILES['intro_img']) && $_FILES['intro_img']['size'] > 0) {
            $data['intro_img'] = Qnupload::upload($_FILES['intro_img'], null, 'signin/intro');
        }
        if (!empty($_FILES['teacher_img']) && $_FILES['teacher_img']['size'] > 0) {
            $data['teacher_img'] = Qnupload::upload($_FILES['teacher_img'], null, 'signin/teacher');
        }
        if (!empty($_FILES['living_img']) && $_FILES['living_img']['size'] > 0) {
            $data['living_img'] = Qnupload::upload($_FILES['living_img'], null, 'signin/living');
        }
        if (!empty($_FILES['prize_img']) && $_FILES['prize_img']['size'] > 0) {
            $data['prize_img'] = Qnupload::upload($_FILES['prize_img'], null, 'signin/prize');
        }
        if (!empty($_FILES['award_img']) && $_FILES['award_img']['size'] > 0) {
            $data['award_img'] = Qnupload::upload($_FILES['award_img'], null, 'signin/award');
        }
        if (!empty($_FILES['user_info_title']) && $_FILES['user_info_title']['size'] > 0) {
            $data['user_info_title'] = Qnupload::upload($_FILES['user_info_title'], null, 'signin/user_info');
        }
        $cidArr = $request->input('cids');
        foreach ($cidArr as $item) {
            $signinGameConfig = new SigninGameConfig();
            $signinGameConfig->cid = $item;
            $signinGameConfig->win_num = $request->input('win_num');
            $signinGameConfig->fri_share_title = $request->input('fri_share_title');
            $signinGameConfig->fri_share_desc = $request->input('fri_share_desc');
            $signinGameConfig->fri_circle_share_title = $request->input('circle_share_desc');
            if (isset($data['sign_share_picture'])) {
                $signinGameConfig->share_img = $data['sign_share_picture'];
            }

            if (isset($data['brand_img'])) {
                $signinGameConfig->brand_img = $data['brand_img'];
            }

            if (isset($data['rule_img'])) {
                $signinGameConfig->rule_img = $data['rule_img'];
            }

            if (isset($data['intro_img'])) {
                $signinGameConfig->intro_img = $data['intro_img'];
            }

            if (isset($data['teacher_img'])) {
                $signinGameConfig->teacher_img = $data['teacher_img'];
            }

            if (isset($data['living_img'])) {
                $signinGameConfig->living_img = $data['living_img'];
            }

            if (isset($data['prize_img'])) {
                $signinGameConfig->prize_img = $data['prize_img'];
            }

            if (isset($data['award_img'])) {
                $signinGameConfig->award_img = $data['award_img'];
            }

            if (isset($data['user_info_title'])) {
                $signinGameConfig->user_info_title = $data['user_info_title'];
            }

            $signinGameConfig->save();
        }
        if ($signinGameConfig) {
            return view('admin.error', ['msg' => '新建游戏成功', 'url' => '/admin/signin/index']);
        } else {
            return view('admin.error', ['msg' => '已更新', 'url' => '/admin/course/signin/1/'.$request->input('cid')]);
        }

    }

    private function showAjax ($status = 0, $msg = '', $data = [])
    {
        $result = [
          'status' => $status,
          'msg' => $msg,
          'data' => $data
        ];
        return response()->json($result);
    }

    /**
     * @param $signId
     * @return mixed
     * @description 获取某一游戏排名
     */
    public function getSignOrderById ($signId)
    {
        //获取当前
        $signItem = SigninItem::where('id', '=', $signId)->first();
        $signNum = $signItem->signin_num;
        $signId = $signItem->id;
        $courseId = $signItem->cid;
        $created_at = $signItem->created_at;
        $before = SigninItem::where('cid', '=', $courseId)->where('signin_num', '>', $signNum)->count();
        $equal = SigninItem::where('cid', '=', $courseId)->where('signin_num', '=', $signNum)->where('created_at', '<', $created_at)->count();
        return  $before + $equal + 1;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @description 获取领奖用户信息
     */
    public function victoryInfo(Request $request)
    {
        $id = $request->input('id');
        $signinItem = SigninItem::where('id', '=', $id);
        if ($signinItem->count() == 0) {
            return $this->showAjax(1,'游戏记录不存在');
        }
        //获取领奖信息
        $signinWinRecord = SigninWinRecords::where('signin_item_id', '=', $id);
        if ($signinWinRecord) {
            if ($signinWinRecord->count() == 0) {
                return $this->showAjax(3, '暂未提交领奖信息');
            } else {
                return $this->showAjax(0, '', $signinWinRecord->first());
            }
        } else {
            return $this->showAjax(1, '领奖记录获取失败');
        }
    }

    public function printSignList (Request $request, $cid)
    {
        if (empty($cid) || !isset($cid)) {
            return view('admin.error', ['msg' => '游戏ID未传入']);
        }

        $filename = "{$cid}_sign_list.csv";
        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0'
            , 'Content-type' => 'text/csv'
            , 'Content-Disposition' => 'attachment; filename=' . $filename
            , 'Expires' => '0'
            , 'Pragma' => 'public',
        ];
        $columnNames['title'] = [
                'name1' =>'游戏ID',
                'name2' =>'签到数量',
                'name3' =>'课程id',
                'name4' =>'用户id',
                'name5' =>'用户openID',
                'name6' =>'真实姓名',
                'name7' =>'手机号',
                'name8' =>'收货地址',
                'name9' =>'备注',
                'name10' =>'创建时间',
            ];
        $query = DB::table('signin_items')
            ->leftJoin('user', 'user.id', '=', 'signin_items.start_uid')
            ->where('signin_items.cid', $cid)
            ->select('signin_items.id', 'signin_items.signin_num', 'signin_items.cid', 'signin_items.start_uid', 'user.openid', 'signin_items.created_at')
            ->orderBy('signin_items.signin_num', 'desc')
            ->get();
        $query = collect($query)->toArray();
        $array = [];
        foreach ($query as $key =>$item) {
            $signinWinRecords = SigninWinRecords::where('signin_item_id', '=', $item->id)->first();
            $array[$key]['id'] = $item->id;
            $array[$key]['signin_num'] = $item->signin_num;
            $array[$key]['cid'] = $item->cid;
            $array[$key]['start_uid'] = $item->start_uid;
            $array[$key]['openid'] = $item->openid;
            $array[$key]['realname'] = isset($signinWinRecords->realname) ? $signinWinRecords->realname : '';
            $array[$key]['mobile'] = isset($signinWinRecords->mobile) ? $signinWinRecords->mobile : '';
            $array[$key]['address'] = isset($signinWinRecords->address) ? $signinWinRecords->address : '';
            $array[$key]['remark'] = isset($signinWinRecords->remark) ? $signinWinRecords->remark : '';
            $array[$key]['created_at'] = $item->created_at;
        }
        $query = array_merge ($columnNames,$array);

        Excel::create($filename, function($excel) use ($query, $columnNames){
                $excel->sheet('score', function($sheet) use ($query, $columnNames) {
                $sheet->rows($query);
            });
        })->export('csv');

    }
}
