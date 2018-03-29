<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\CreateTemplateMessageByOpenid;
use App\Jobs\CreateTplmsgFromCourse;
use App\Jobs\CreateSQTplmsgFromCourse;
use App\Jobs\CreateSQTplmsgByOpenid;
use App\Models\Admin;
use App\Models\AreaCity;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseApply;
use App\Models\CourseCat;
use App\Models\CourseTag;
use App\Models\OnlineStatistics;
use App\Models\ShortUrl;
use App\Models\Tag;
use App\Models\RecommendCourse;
use App\Models\UserCourse;
use App\Services\CourseService;
use App\Services\Qnupload;
use App\Services\WxWyeth;
use Endroid\QrCode\QrCode;
use Excel;
use GuzzleHttp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session; // cat
use Input; // cat
use Redirect;
use App\Jobs\SendSQTemplateMessage; //sendtemplate
use App\Services\MobileQQ;
use App\Services\LiveService;
use App\Services\CounterService;
use App\Services\QcloudService;
use App;
use App\Services\TagService;

/**
 * Class CourseController
 * @package App\Http\Controllers\Admin
 */
class CourseController extends BaseController
{
    public $status_arr = [0 => '无效', 1 => '有效'];

    public function index(Request $request)
    {
        $user_info = Session::get('admin_info');
        $per_page = 10;
        $params = [];
        $params['title'] = $request->input('title');
        $params['display_status'] = $request->input('display_status');
        $params['start_day'] = $request->input('start_day');
        $params['id'] = $request->input('id');
        $params['teacher_name'] = $request->input('teacher_name');
        $params['cid'] = $request->input('cid');
        $params['sort'] = $request->input('sort');
        $params['tid'] = $request->input('tid');

        if ($params['tid']) {
            $cids = CourseTag::where('tid', $params['tid'])->lists('cid');
            if (count($cids) > 0) {
                $md = Course::whereIn('id', $cids)->where('id', '<>', 40);
            }
        } else {
            $md = Course::where('id', '>', 0);
        }
        !empty($params['title']) && $md->where("title", "like", "%" . $params['title'] . "%");
        if ($params['display_status'] === '0' || $params['display_status'] === '1') {
            $md->where("display_status", "=", $params['display_status']);
        }
        !empty($params['start_day']) && $md->where("start_day", "=", $params['start_day']);
        !empty($params['id']) && $md->where("id", "=", $params['id']);
        !empty($params['teacher_name']) && $md->where("teacher_name", "=", $params['teacher_name']);
        !empty($params['cid']) && $md->where("cid", "=", $params['cid']);

        if (!empty($params['sort']) && $params['sort'] == 'start_day') {
            $list = $md->orderBy('start_day', 'desc')->paginate($per_page);
        } else {
            $list = $md->orderBy('id', 'desc')->paginate($per_page);
        }
        if (!empty($list)) {
            foreach ($list as $k => $v) {
                $list[$k]->ware_count = DB::table('courseware')->where('cid', $v->id)->count();
                $list[$k]->review_id = DB::table('course_review')->where('cid', $v->id)->pluck('id');
                $list[$k]->realnum = CounterService::courseRegAllGet($v->id);
            }
        }
        return view('admin.course.index', ['list' => $list, 'status_arr' => $this->status_arr, 'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }


    public function indexTest(Request $request)
    {
        $user_info = Session::get('admin_info');
        $per_page = 10;
        $params = [];
        $params['title'] = $request->input('title');
        $params['number'] = $request->input('number');
        $params['start_day'] = $request->input('start_day');
        $params['id'] = $request->input('id');
        $params['sort'] = $request->input('sort');
        $md = Course::select('course.*','course_review.audio')
            ->leftjoin('course_review', 'course_review.cid', '=', 'course.id')
            ->where('course_review.audio', '!=', '');
        !empty($params['title']) && $md->where("title", "like", "%" . $params['title'] . "%");
        if (!empty($params['number']) || $params['number'] === '0') {
            $md->where("number", "=", $params['number']);
        }
        !empty($params['start_day']) && $md->where("start_day", "=", $params['start_day']);
        !empty($params['id']) && $md->where("course.id", "=", $params['id']);
        if (!empty($params['sort']) && $params['sort'] == 'start_day') {
            $list = $md->orderBy('course.start_day', 'desc')->paginate($per_page);
        } else {
            $list = $md->orderBy('course.id', 'desc')->paginate($per_page);
        }
        if (!empty($list)) {
            foreach ($list as $k => $v) {
                $list[$k]->ware_count = DB::table('courseware')->where('cid', $v->id)->count();
                $list[$k]->review_id = DB::table('course_review')->where('cid', $v->id)->pluck('id');
                $list[$k]->realnum = CounterService::courseRegAllGet($v->id);
            }
        }
        return view('admin.course.indexTest', ['list' => $list, 'status_arr' => $this->status_arr, 'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }
    
    public function addAudioDetail(Request $request){
        $cid = $request->input('cid');
        $course = Course::find($cid);
        if (!$course){
            $result = ['status' => 500, 'code' => '课程不存在'];
            return response()->json($result);
        }
        $detail = $request->input('detail');
        $course->audio_detail = $detail;
        $course->save();
        $result = ['status' => 200, 'code' => '保存成功'];
        return response()->json($result);
    }
    

    /*
     * 删除已上传的课件
     */
    public function del_img($id)
    {
        $re = array('status' => 0, 'msg' => '删除失败');
        $id = intval($id);
        DB::table('courseware')->where('id', $id)->delete();
        exit(json_encode(array('status' => 1, 'msg' => '已删除')));
    }

    /*
     * 解绑教师
     */
    public function unbindt($id)
    {
        $id = intval($id);
        $result = ['status' => 0, 'msg' => '解绑失败'];
        $info = DB::table('course')->where('id', $id)->first();
        if (empty($info)) {
            echo json_encode(['status' => 0, 'msg' => '课程不存在']);
            return;
        }
        $r = DB::table('course')->where('id', $id)->update(array('teacher_uid' => 0));
        if ($r) {
            $result = ['status' => 1, 'msg' => '已解绑'];
        }
        echo json_encode($result);
    }

    /*
     * 解绑主持人
     */
    public function unbinda($id)
    {
        $id = intval($id);
        $result = ['status' => 0, 'msg' => '解绑失败'];
        $info = DB::table('course')->where('id', $id)->first();
        if (empty($info)) {
            echo json_encode(['status' => 0, 'msg' => '课程不存在']);
            return;
        }
        $r = DB::table('course')->where('id', $id)->update(array('anchor_uid' => 0));
        if ($r) {
            $result = ['status' => 1, 'msg' => '已解绑'];
        }
        echo json_encode($result);
    }

    /*
     * src为要插入数据库的地址
     * img_url为显示的地址
     */
    public function save_upload($id)
    {
        $id = intval($id);
        $re = array('status' => 0, 'msg' => '上传失败.', 'src' => '', 'img_url' => '');
        if (empty($id)) {
            $re['msg'] = '参数错误.';
            exit(json_encode($re));
        }
        $data = array();
        $data['cid'] = $id;
        if ($_FILES['Filedata']['size'] > 0) {
            $data['img'] = Qnupload::upload($_FILES['Filedata']);
            if (empty($data['img'])) {
                $re['msg'] = '图片上传失败.';
                exit(json_encode($re));
            }
        } else {
            $re['msg'] = '必须上传图片.';
            exit(json_encode($re));
        }
        $reid = DB::table('courseware')->insertGetId($data);
        if (empty($reid)) {
            $re['msg'] = '图片上传失败.';
            exit(json_encode($re));
        }

        $re['status'] = 1;
        $re['src'] = $data['img'];
        $re['img_id'] = $reid;
        $re['img_url'] = $data['img'];
        $re['msg'] = '上传成功.';
        exit(json_encode($re));
    }

    public function upload_att($id)
    {
        $id = intval($id);
        if (empty($id)) {
            return view('admin.error', ['msg' => '参数错误', 'url' => 'no']);
        }
        $info = Course::where("id", $id)->first();
        if (empty($info)) {
            return view('admin.error', ['msg' => '数据不存在', 'url' => 'no']);
        }
        $list = App\Models\Courseware::where("cid", $id)->get();
        $qiniu_domain = config('qiniu.domain');
        return view('admin.course.upload_att', ['info' => $info, 'list' => $list, 'id' => $id, 'qiniu_domain' => $qiniu_domain]);
    }

    public function view_qrcode(Request $request, $id)
    {
        $id = intval($id);
        $info = DB::table('course')->where("id", $id)->first();
        if (empty($info)) {
            return view('admin.error', ['msg' => '数据不存在', 'url' => 'no']);
        }
        $type = $request->input('type');
        if ($type == 'teacher') {
            // 讲师
            $wx_info = DB::table('user')->where("id", $info->teacher_uid)->first();
        } else if ($type == 'anchor') {
            // 主持人
            $wx_info = DB::table('user')->where("id", $info->anchor_uid)->first();
        } else {
            return view('admin.error', ['msg' => 'type error', 'url' => 'no']);
        }

        return view('admin.course.view_qrcode', [
            'info' => $info,
            'wx_info' => $wx_info,
            'id' => $id,
            'type' => $type,
        ]);
    }

    public function qr(Request $request, $id)
    {
        $qcloud = new QcloudService;
        $bizid = config('course.qcloud_bizid');
        $key = config('course.qcloud_key');
        $time = date("Y-m-d 23:59:59");
        $environment = App::environment();
        $streamId = 'wyeth_'.$environment.'_'.$id;
        $url = $qcloud->getPushUrl($bizid, $streamId, $key, $time);
        $qrCode = new QrCode();
        $qrCode
            ->setText($url)
            ->setSize(300)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabel('')
            ->setLabelFontSize(16)
            ->render();
    }

    public function edit(Request $request, $id = 0)
    {
        $new_ids = Tag::getNewTag();
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
        $area_city = AreaCity::get()->toArray();
        $info->stage = $this->redo_stage($info->stage);

        if ($info->extend && isset($info->extend->is_switch_audio_source)) {
            $info->isSwitchAudioSource = $info->extend->is_switch_audio_source;
        } else {
            $info->isSwitchAudioSource = 'no';
        }

        if ($info->banners) {
            $info->banners = explode(',', $info->banners);
        }

        //读取课程分类(套课)
        $courseCats = CourseCat::get();

        // 课程相关tag
        $courseTags = CourseTag::where('cid', $info->id)->get()->toArray();

        $brands = App\Models\Brand::all();

//        $display_tags = array();
//        $d_tags = explode(',', $info->display_tags);
//        foreach ($d_tags as $t) {
//            $d_t = DB::table('display_tags')->where('id', $t)->first();
//            if ($d_t) {
//                $display_tags[] = $d_t;
//            }
//        }

        $dataArr = array(
            'info' => $info,
            'id' => $id,
            'area_city' => $area_city,
            'courseCats' => $courseCats,
            'new_ids' => $new_ids,
            'courseTags' => $courseTags,
            'brands' => $brands,
//            'display_tags' => $display_tags
        );

        return view('admin.course.edit', $dataArr)
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    /**
     * 保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $user_info = Session::get('admin_info');
        $id = intval($request->input('id'));

        //检查课程期数
//        if ($request->has('number')) {
//            $number = $request->input('number');
//            $query = Course::where(['number' => $number]);
//            if ($id) {
//                $query = $query->where('id', '<>', $id);
//            }
//            if ($query->first()) {
//                return view('admin.error', ['msg' => '课程期数已经存在']);
//            }
//        }

        $data = $request->all();
        if (empty($data['display_status'])) {
            $data['display_status'] = 0;
        }
        if (!empty($data['title'])) {
            //$data['stage'] = $this->do_stage($data['stage']);
            if($data['fromType'] == 100){
                $data['stage_from'] = 100;
            } elseif ($data['fromType'] == 2){
                $data['stage_from'] = (int)'2'.$data['fromMonth'];
            } elseif ($data['fromType'] == 3){
                $data['stage_from'] = (int)'3'.$data['fromYear'];
            }
            if($data['toType'] == 100){
                $data['stage_to'] = 100;
            } elseif ($data['toType'] == 2){
                $data['stage_to'] = (int)'2'.$data['toMonth'];
            } elseif ($data['toType'] == 3){
                $data['stage_to'] = (int)'3'.$data['toYear'];
            }
            unset($data['fromType']);
            unset($data['fromYear']);
            unset($data['fromMonth']);
            unset($data['toType']);
            unset($data['toMonth']);
            unset($data['toYear']);
            if (!empty($_FILES['img']) && $_FILES['img']['size'] > 0) {
                $data['img'] = Qnupload::upload($_FILES['img'], null, 'course/img');
            }
            if (!empty($_FILES['ad_img']) && $_FILES['ad_img']['size'] > 0) {
                $data['ad_img'] = Qnupload::upload($_FILES['ad_img'], null, 'course/img');
            }
            if (isset($data['qrcode_type']) && $data['qrcode_type'] == '1' && !empty($_FILES['qrcode']) && $_FILES['qrcode']['size'] > 0) {
                $data['qrcode'] = Qnupload::upload($_FILES['qrcode'], null, 'course/qrcode');
            } else {
                unset($data['qrcode']);
            }

            if (!empty($_FILES['teacher_avatar']) && $_FILES['teacher_avatar']['size'] > 0) {
                $data['teacher_avatar'] = Qnupload::upload($_FILES['teacher_avatar'], null, 'course/teacher');
            }
            if ($_FILES['share_picture']['size'] > 0) {
                $data['share_picture'] = Qnupload::upload($_FILES['share_picture'],  null, 'course/share');
            }
            if (isset($_FILES['audio']['size']) && $_FILES['audio']['size'] > 0) {
                $data['audio'] = Qnupload::upload($_FILES['audio'],  null, 'course/audio');
            }
            if ($_FILES['living_share_picture']['size'] > 0) {
                $data['living_share_picture'] = Qnupload::upload($_FILES['living_share_picture'],  null, 'course/shareliving');
            }
            if (!empty($_FILES['banner']) && count($data['banner']) > 0) {
                $temp = [];
                for ($i = 0;  $i < count($_FILES['banner']['tmp_name']); $i++) {
                    if ($data['banner'][$i] != null) {
                        $temp[$i] = Qnupload::uploadTmp($_FILES['banner']['tmp_name'][$i], null, '/course/' . $_FILES['banner']['name'][$i]);
                    }
                }
                $data['banners'] = implode(',', $temp);
            }
            unset($data['banner']);
            if (isset($data['img']) && empty($data['img'])) {
                unset($data['img']);
            }
            if (isset($data['ad_img']) && empty($data['ad_img'])) {
                unset($data['ad_img']);
            }
            if (isset($data['teacher_avatar']) && empty($data['teacher_avatar'])) {
                unset($data['teacher_avatar']);
            }
            if (isset($data['share_picture']) && empty($data['share_picture'])) {
                unset($data['share_picture']);
            }
            if (isset($data['living_share_picture']) && empty($data['living_share_picture'])) {
                unset($data['living_share_picture']);
            }
            if (isset($data['audio']) && empty($data['audio'])) {
                unset($data['audio']);
            }
            if (isset($data['status']) && empty($data['status'])) {
                unset($data['status']);
            }
            //加入套课参数
            //$data['cid'] = $data['cid'];
            if (isset($data['is_switch_audio_source'])) {
                $isSwitchAudioSource = $data['is_switch_audio_source'];
                unset($data['is_switch_audio_source']);
            }else{
                $isSwitchAudioSource = 'no';
            }

            unset($data['choose']);

            unset($data['id']);
            $type = isset($data['type']) ? $data['type'] : 1;
            unset($data['type']);

            $teacher_id = 0;
            if (isset($data['teacher_id'])) {
                $teacher_id = $this->getIdFromStr($data['teacher_id']);
                unset($data['teacher_id']);
            }
            $tags = [];
            $weights = [];
            if (isset($data['tags'])) {
                $tags = $data['tags'];
                foreach ($tags as $index => $tag) {
                    $tags[$index] = $this->getIdFromStr($tag);
                    $weights[$index] = $data['weights'][$index];
                }
                unset($data['tags']);
                unset($data['weights']);
            }
            $pre_tags = [];
            if (isset($data['pre_tag'])) {
                $pre_tags = $data['pre_tag'];
                unset($data['pre_tag']);
            }
            $dis_tags = [];
            if (isset($data['dis_tags'])) {
                $dis_tags = $data['dis_tags'];
                unset($data['dis_tags']);
            }

            if ($id) {
                $course = Course::find($id);
                if (!$course->extend) {
                    $course->extend = (object)array();
                }
                $extend = $course->extend;
                $extend->is_switch_audio_source = $isSwitchAudioSource;
                $course->extend = $extend;
                $course->type = $type;
                $banners = explode(',', $course->banners);
                $course->save();
                DB::table('course')->where('id', $id)->update($data);

                $this->setTeachers($id, $teacher_id);
                $this->setTags($id, $tags, $weights);
                $this->setAgeTags($id, $pre_tags);
                $this->setDisplayTags($id, $dis_tags);

//                $this->store_tags($id, $tags);
                //上传opensearch文档数据
                CourseService::updateSearchDoc($id);
                return view('admin.error', ['msg' => '已更新', 'url' => '/admin/course']);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $id = DB::table('course')->insertGetId($data);
                $course = Course::find($id);
                $course->extend = (object)array();
                $extend = $course->extend;
                $extend->is_switch_audio_source = $isSwitchAudioSource;
                $course->extend = $extend;
                $course->type = $type;
                $course->save();

                $this->setTeachers($id, $teacher_id);
                $this->setTags($id, $tags, $weights);
                $this->setAgeTags($id, $pre_tags);
                $this->setDisplayTags($id, $dis_tags);

//                $this->store_tags($id, $tags);
                //上传opensearch文档数据
                CourseService::addSearchDoc($id);
                return view('admin.error', ['msg' => '添加成功，进入课程列表', 'url' => '/admin/course']);
            }
        }
    }

    /**
     * 保存tags
     * @return [type] [description]
     */
    private function store_tags($cid, $tags)
    {

        $course = Course::find($cid);
        // 先删除所有的关联
        $course->tags()->detach();
        if (count($tags)) {
            foreach ($tags as $value) {
                $data = ['cid' => $cid, 'tid' => 0];
                if (substr($value, 0, 3) === 'id#') {
                    $data['tid'] = intval(substr($value, 3));
                } else {
                    $tag = Tag::firstOrCreate(['name' => $value]);
                    $data['tid'] = $tag->id;
                }
                CourseTag::firstOrCreate($data);
            }
        }
    }

    private function getIdFromStr ($str) {
        if (strpos($str, '#')) {
            return explode('#', $str)[1];
        }
        return $str;
    }

    private function setTags ($cid, $tags, $weights) {
//        $data = $request->all();
//        $cid = $data['id'];
        if ($cid) {
            $course = Course::find($cid);

            $id_arrs = [];
            $courseTags = CourseTag::where(['cid' => $cid, 'type' => Tag::TAG_CONTENT])->get();
            $tag_weight_arr_old = array();
            if ($courseTags) {
                foreach ($courseTags as $item) {
                    $id_arrs[] = $item->tid;
                    $tag_weight_arr_old[$item->tid] = 0;
                }
            }
            TagService::updateItem($cid, $tag_weight_arr_old);
            // 先删除所有的关联
            if (count($id_arrs)) {
                $course->tags()->detach($id_arrs);
            }

//            if (array_key_exists('tags', $data)) {
//                $tags = $data['tags'];

            $tag_weight_arr = array();
            if (count($tags)) {
                foreach ($tags as $index => $item) {
                    $weight = (float)$weights[$index];
                    $arr = ['cid' => $cid, 'tid' => $item, 'weight' => $weight, 'type' => Tag::TAG_CONTENT];
                    CourseTag::firstOrCreate($arr);
                    $tag_weight_arr[$item] = $weight;
                }
            }
            TagService::updateItem($cid, $tag_weight_arr);
//            }
            return $this->ajaxMsg('保存成功', 1);
        } else {
            return $this->ajaxError('请先创建课程');
        }

    }

    private function setAgeTags ($cid, $tids) {
//        $data = $request->all();
//        $cid = $data['cid'];
        if ($cid) {
//            $tids = $data['tids'];
            $course = Course::find($cid);

            $id_arrs = [];
            $courseTags = CourseTag::where(['cid' => $cid, 'type' => Tag::TAG_PREGNANT])->get();
            if ($courseTags) {
                foreach ($courseTags as $item) {
                    $id_arrs[] = $item->tid;
                }
            }
            // 先删除所有的关联
            if (count($id_arrs)) {
                $course->tags()->detach($id_arrs);
            }

            $stage = '';
            foreach ($tids as $index => $item ) {
                $tag = Tag::find($item);
                $stage .= $tag->name;
                if ($index != count($tids) - 1) {
                    $stage .= ',';
                }
                $insert_arr = ['cid' => $cid, 'tid' => $item, 'weight' => 1, 'type' => Tag::TAG_PREGNANT];
                CourseTag::firstOrCreate($insert_arr);
            }
            $course->stage = $stage;
            $course->save();

            return $this->ajaxMsg('保存成功', 1);
        } else {
            return $this->ajaxError('请先创建课程');
        }
    }

    public function setTeachers(/*Request $request*/$cid, $tid) {
//        $data = $request->all();
//        $cid = $data['cid'];
        if ($cid) {
//            $tid = $data['tid'];
            $course = Course::find($cid);

            $id_arrs = [];
            $courseTags = CourseTag::where(['cid' => $cid, 'type' => Tag::TAG_TEACHER])->get();
            if ($courseTags) {
                foreach ($courseTags as $item) {
                    $id_arrs[] = $item->tid;
                }
            }
            // 先删除所有的关联
            if (count($id_arrs)) {
                $course->tags()->detach($id_arrs);
            }

            $lecturer = App\Models\Lecturer::where('tid', $tid)->first();
            $course->teacher_name = $lecturer->name;
            $course->teacher_avatar = $lecturer->avatar;
            $course->teacher_hospital = $lecturer->hospital;
            $course->teacher_position = $lecturer->position;
            $course->teacher_desc = $lecturer->desc;
            $course->save();

            $arr = ['cid' => $cid, 'tid' => $tid, 'weight' => 1, 'type' => Tag::TAG_TEACHER];
            CourseTag::firstOrCreate($arr);

            TagService::updateTag();

            return $this->ajaxMsg('保存成功', 1);
        } else {
            return $this->ajaxError('请先创建课程');
        }
    }

    private function setDisplayTags (/*Request $request, */$cid, $display_tags) {
//        $data = $request->all();
//        $cid = $data['id'];
        if ($cid) {
            $course = Course::find($cid);

            $id_arrs = [];
            $courseTags = CourseTag::where(['cid' => $cid, 'type' => Tag::TAG_DISPLAY])->get();
            if ($courseTags) {
                foreach ($courseTags as $item) {
                    $id_arrs[] = $item->tid;
                }
            }
            // 先删除所有的关联
            if (count($id_arrs)) {
                $course->tags()->detach($id_arrs);
            }

//            $display_tags = explode(',', $data['display_tags']);
            if (count($display_tags)) {
                foreach ($display_tags as $item) {
                    $arr = ['cid' => $cid, 'tid' => $item, 'weight' => 1, 'type' => Tag::TAG_DISPLAY];
                    CourseTag::firstOrCreate($arr);
                }
            }

            return $this->ajaxMsg('保存成功', 1);
        } else {
            return $this->ajaxError('请先完成课程创建');
        }
    }

    /**
     * 课程在线人数图表
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function statistics(Request $request, $id)
    {
        $course = Course::where("id", $id)->first();
        if (empty($course)) {
            return view('admin.error', ['msg' => '数据不存在']);
        }
        $user_info = Session::get('admin_info');
        $data = [
            'user_info' => $user_info,
            'menu' => Session::get('menu'),
        ];

        $statistics = OnlineStatistics::select('time', 'count')->where('cid', $id)->get();
        if ($statistics->isEmpty()) {
            return view('admin.error', ['msg' => '本节课暂时没有数据']);
        }
        $chat_data = [];
        foreach ($statistics as $value) {
            $chat_data[] = [$value->time, $value->count];
        }
        $data['chat'] = $chat_data;
        return view('admin.course.statistics', $data);
    }

    public function delete($id)
    {
        $id = intval($id);
        $course  = Course::find($id);
        // 先删除所有的关联
        $course->tags()->detach();
        $course->delete();
        $refer = !empty($_SERVER['HTTP_REFER']) ? $_SERVER['HTTP_REFER'] : '';
        return view('admin.error', ['msg' => '已删除', 'url' => $refer]);
    }

    public function delete_all(Request $request)
    {
        $params = $request->all();
        $id_arr = $params['id'];
        if (empty($id_arr)) {
            return view('admin.error', ['msg' => '请选择要删除的对象']);
        }
        DB::table('course')->whereIn('id', $id_arr)->delete();
        $refer = !empty($_SERVER['HTTP_REFER']) ? $_SERVER['HTTP_REFER'] : '';
        return view('admin.error', ['msg' => '已删除', 'url' => $refer]);
    }

    public function notify_setting(Request $request, $id)
    {
        $user_info = Session::get('admin_info');
        $id = intval($id);
        empty($id) && Redirect('/admin/course/add');
        $course = Course::where("id", $id)->first();
        if (empty($course)) {
            return view('admin.error', ['msg' => '数据不存在']);
        }
        return view('admin.course.notify_setting', ['info' => $course, 'id' => $id])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function preview_tplmsg(Request $request)
    {
        $url = '';
        if ($request->has('notify_url')) {
            $url = $this->_addUrlParams($request->input('notify_url'), $request->input('cid'));
            if (($check = $this->checkUrl($url, $request->input('cid'))) !== true){
                return $check;
            }
        }
        $params = [
            'title' => $request->input('notify_title'),
            'content' => $request->input('notify_content'),
            'odate' => $request->input('notify_odate'),
            'address' => $request->input('notify_address'),
            'remark' => $request->input('notify_remark', ''),
            'url' => $url,
            'openid' => trim($request->input('notify_openid')),
        ];
        $template_id = intval($request->input('template_id', 1));
        $wxWyeth = new WxWyeth();
        return $wxWyeth->pushpushCustomMessage($params, $template_id, false);
    }

    // 保存消息模板信息
    public function tplmsg_save(Request $request)
    {
        $id = $request->input('cid');
        $course = Course::where("id", $id)->first();
        if (empty($course)) {
            return view('admin.error', ['msg' => '数据不存在']);
        }
        $course->notify_title = $request->input('notify_title');
        $course->notify_content = $request->input('notify_content');
        $course->notify_remark = $request->input('notify_remark');
        $course->notify_odate = $request->input('notify_odate');
        $course->notify_address = $request->input('notify_address');
        $course->notify_template_id = $request->input('template_id');
        $course->remark = $request->input('remark');

        if ($request->has('notify_url')) {
            $course->notify_url = $this->_addUrlParams($request->input('notify_url'), $id);
            if (($check = $this->checkUrl($course->notify_url, $course->id)) !== true){
                return $check;
            }
        } else {
            $course->notify_url = '';
        }

        $course->save();
        $result = [
            'status' => 1,
        ];
        return response()->json($result);
    }

    // 发送模板消息
    public function send_tplmsg(Request $request)
    {
        $id = $request->input('cid');
        $course = Course::where("id", $id)->first();
        if (empty($course)) {
            $result = [
                'status' => 0,
                'msg' => '数据不存在',
            ];
            return response()->json($result);
        }

        if ($request->has('notify_content')) {

            $course->notify_title = $request->input('notify_title');
            $course->notify_content = $request->input('notify_content');
            $course->notify_remark = $request->input('notify_remark');
            $course->notify_odate = $request->input('notify_odate');
            $course->notify_address = $request->input('notify_address');
            $course->notify_template_id = $request->input('template_id');

            if ($request->has('notify_url')) {
                $course->notify_url = $this->_addUrlParams($request->input('notify_url'), $id);
                if (($check = $this->checkUrl($course->notify_url, $course->id)) !== true){
                    return $check;
                }
            } else {
                $course->notify_url = '';
            }
            $course->save();

            $template_id = intval($request->input('template_id', 1));
            $send_type = intval($request->input('send_type', 1));
            if ($send_type == 2) {
                // 指定openid
                $openids = $request->input('openids');
                if (!$openids) {
                    return $this->ajaxError('发送失败，没有指定openid');
                }
                $openid_list = explode("\n", $openids);
                if (count($openid_list) > 3000) {
                    return $this->ajaxError('openid个数不能超过3000条');
                }
                $openidArr = [];
                foreach ($openid_list as $k => $v) {
                    $v = trim($v);
                    if ($v && strlen($v) > 10 && strlen($v) < 60) {
                        $openidArr[] = $v;
                    }
                }
                $openidsChunks = array_chunk($openidArr, 500);
                if ($openidsChunks) {
                    foreach ($openidsChunks as $openidArr) {
                        $this->dispatch(new CreateTemplateMessageByOpenid($course->id, $openidArr, $template_id));
                    }
                }
                return $this->ajaxMsg('发送成功');

            } else {
                // 模版消息发送给哪些用户
                $inclass_status = $request->input('inclass_status', 1);
                $user_type = $request->input('user_shop', 1);
                $sign_start = $request->input('sign_start', null);
                $sign_end = $request->input('sign_end', null);
                if ($sign_start && !strtotime($sign_start)){
                    return $this->ajaxError('推送开始时间错误');
                }
                if ($sign_end && !strtotime($sign_end)){
                    return $this->ajaxError('推送结束时间错误');
                }
                if ($sign_end && !$sign_start){
                    return $this->ajaxError('没有开始时间');
                }
                $tpl_remark = '['.date('Y-m-d H:i:s')."] 推送模板消息$template_id";
                if ($sign_start || $sign_end){
                    //保存remark
                    $tpl_remark .= "推送报名时间段$sign_start 到 $sign_end";
                }
                $course->remark .= $tpl_remark."\n";
                $course->save();

                // 请求发送模版消息
                $this->_syncSendMsg($course, $user_type, $inclass_status, $template_id, $sign_start, $sign_end);
                return $this->ajaxMsg('发送成功');
            }

            return $this->ajaxError('失败');
        }
    }

    private function checkUrl($url, $id){
        if (strlen($url) > 255){
            return $this->ajaxError('url不能超过255个字符');
        }
        $url_query = parse_url($url, PHP_URL_QUERY);
        parse_str($url_query);
        if (!isset($cid) || $cid != $id){
            return $this->ajaxError('url未带cid或与课程id不同');
        }
        return true;
    }

    //传入数组，返回字符串
    public function do_stage($stage_arr)
    {
        $string = $stage_arr[1][1];
        if ($stage_arr[1][1] == '孕中') {
            $string .= ' ' . $stage_arr[1][2];
        } elseif ($stage_arr[1][1] == '宝宝') {
            $string .= ' ' . $stage_arr[1][3];
        }

        $string .= ' - ' . $stage_arr[2][1];
        if ($stage_arr[2][1] == '孕中') {
            $string .= ' ' . $stage_arr[2][2];
        } elseif ($stage_arr[2][1] == '宝宝') {
            $string .= ' ' . $stage_arr[2][3];
        }
        return $string;
    }

    //传入字符串，返回数组
    public function redo_stage($string)
    {
        $re = [];
        $tmp_1 = explode('-', $string);
        if (isset($tmp_1[0])) {
            $tmp_1[0] = trim($tmp_1[0]);
            $re[1] = explode(' ', $tmp_1[0]);
            !isset($re[1][1]) && $re[1][1] = '';
        } else {
            $re[1] = array(0 => '', 1 => '');
        }
        if (isset($tmp_1[1])) {
            $tmp_1[1] = trim($tmp_1[1]);
            $re[2] = explode(' ', $tmp_1[1]);
            !isset($re[2][1]) && $re[2][1] = '';
        } else {
            $re[2] = array(0 => '', 1 => '');
        }
        return $re;
    }

    /**
     * 调用队列发送模板消息
     * @param  [type] $cid            课程id
     * @param  [type] $user_shop      有主状态
     * @param  [type] $inclass_status 听课状态
     * @return [type]                 [description]
     */
    private function _syncSendMsg(Course $course, $user_shop, $inclass_status, $template_id, $sign_start = '', $sign_end = '')
    {
        if ($course) {
            $params = [
                'cid' => $course->id,
                'user_shop' => $user_shop,
                'inclass_status' => $inclass_status,
                'template_id' => $template_id,
                'sign_start' => $sign_start,
                'sign_end' => $sign_end,
            ];
            $job = new CreateTplmsgFromCourse($params);
            $this->dispatch($job);
        }
    }

    //补充url参数
    private function _addUrlParams($url, $id)
    {
        $parse_params = parse_url($url);
        if (isset($parse_params['query'])) {
            parse_str($parse_params['query'], $query_params);
            if (!isset($query_params['_hw_c'])) {
                $query_params['_hw_c'] = 'tplmsg';
            }
            if (!isset($query_params['cid'])) {
                $query_params['cid'] = $id;
            }

            $url = $parse_params['scheme'] . "://" . $parse_params['host'] . $parse_params['path'] . '?' . http_build_query($query_params);
        } else {
            $url .= '?_hw_c=tplmsg&cid=' . $id;
        }

        return $url;
    }

    public function applyList(Request $request)
    {
        $user_info = Session::get('admin_info');
        $per_page = 10;
        $params = $request->all();
        if (isset($params['type']) && $params['type'] == 'export') {
            $data[] = [
                '申请课程id',
                '创建账户id',
                '课程名称',
                '课程内容',
                '开始日期',
                '结束日期',
                '开始时间',
                '结束时间',
                '阶段',
                '讲师名称',
                '讲师来源',
                '讲师职位',
                '讲师描述',
                '申请状态',
                '驳回原因',
                '创建日期',
                '更新时间',
            ];

            $from = !empty($params['from']) ? $params['from'] : date('Y-m-d') . ' 00:00:00';
            $to = !empty($params['to']) ? $params['to'] : date('Y-m-d') . ' 23:59:59';

            $sql = " SELECT * FROM course_apply WHERE created_at BETWEEN '{$from}' AND '{$to}'";
            if (!empty($params['status'])) {
                if ($params['status'] != 'all') {
                    $sql .= " AND status = " . $params['status'] . " ";
                }
            }
            $result = DB::select($sql);
            $data = array_merge($data, json_decode(json_encode($result), true));
            Excel::create('course_apply_' . time(), function ($excel) use ($data) {

                $excel->sheet('Sheet1', function ($sheet) use ($data) {

                    $sheet->fromArray($data, null, 'A1', true, false);

                });

            })->export('xls');
        }
        $list = CourseApply::paginate($per_page);
        return view('admin.course.apply_list', ['list' => $list, 'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function applyVerify(Request $request)
    {
        $id = $request->get('id');
        $type = $request->get('type');
        $refuse_reason = $request->get('refuse_reason');

        $info = DB::table('course_apply')->where('id', $id)->first();
        if (empty($info)) {
            return view('admin.error', ['msg' => '申请课程不存在']);
        }
        $data['status'] = $type == 1 ? 1 : 2;
        if (!empty($refuse_reason)) {
            $data['refuse_reason'] = $refuse_reason;
        }

        $r = DB::table('course_apply')->where('id', $id)->update($data);
        if ($r) {
            return view('admin.error', ['msg' => '操作成功', 'url' => '/admin/course/applyList']);
        }
        return view('admin.error', ['msg' => '操作失败', 'url' => '/admin/course/applyList']);
    }

    /**
     * 运营帐号下面的课程列表
     * @return mixed
     */
    public function area_course(Request $request)
    {
        $user_info = Session::get('admin_info');
        $per_page = 2;
        $params = $request->all();
        $md = DB::table('course')->where("id", ">", 0);
        !empty($params['title']) && $md->where("title", "like", "%" . $params['title'] . "%");
        $area_city_ids = DB::table('area_city')->where("area", $user_info->area)->lists('id');
        $list = $md->orderBy('id', 'desc')->whereIn('area_city_id', $area_city_ids)->paginate($per_page);
        if (!empty($list)) {
            foreach ($list as $k => $v) {
                $list[$k]->ware_count = DB::table('courseware')->where('cid', $v->id)->count();
                $list[$k]->review_id = DB::table('course_review')->where('cid', $v->id)->pluck('id');
                $list[$k]->realnum = CounterService::courseRegAllGet($v->id);
            }
        }
        return view('admin.course.area_course', ['list' => $list, 'status_arr' => $this->status_arr, 'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function area_course_detail(Request $request, $id)
    {
        $user_info = Session::get('admin_info');
        $area_city_ids = DB::table('area_city')->where("area", $user_info->area)->lists('id');
        $id = intval($id);
        empty($id) && Redirect('/admin/area_course');
        $info = DB::table('course')->where("id", $id)->whereIn('area_city_id', $area_city_ids)->first();
        if (empty($info)) {
            return view('admin.error', ['msg' => '数据不存在']);
        }

        $data = $request->all();
        empty($data['display_status']) && $data['display_status'] = 0;

        return view('admin.course.area_course_detail', ['info' => $info, 'id' => $id])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    /**
     * category for course
     *
     * $param request $request
     * @return mix
     */
    public function cat(Request $request)
    {
        $per_page = 10;
        $query = CourseCat::where('id', '>', 0);
        $courseCats = $query->orderBy('id', 'desc')->paginate($per_page);
        return view('admin.course.cat', ['courseCats' => $courseCats, 'params' => []])
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    /**
     * 套课编辑
     * @param Request $request
     * @return mixed
     */
    public function catEdit(Request $request, $id = 0)
    {
        $courseCat = CourseCat::find($id);
        if (!$courseCat) {
            return $this->error('课程不存在');
        }

        return view('admin.course.catEdit', ['courseCat' => $courseCat])
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    /**
     * 套课添加
     * @param Request $request
     * @return mixed
     */
    public function catAdd(Request $request)
    {
        $courseCat = new CourseCat();
        return view('admin.course.catEdit', ['courseCat' => $courseCat])
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    /**
     * 套课保存
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function catSave(Request $request)
    {
        $id = intval($request->input('id'));
        $name = $request->input('name');
        $name = mb_substr($name, 0, 15);
        $description = $request->input('description');
        $displayorder = $request->input('displayorder');
        $show_type = $request->input('show_type');
        $price = $request->input('price');
        $link = $request->input('link');

        if (!$id) {
            $courseCat = new CourseCat();
        } else {
            $courseCat = CourseCat::find($id);
            if (!$courseCat) {
                return $this->error('课程不存在');
            }
        }
        $courseCat->name = $name;
        $courseCat->description = $description;
        $courseCat->displayorder = intval($displayorder);
        $courseCat->show_type = intval($show_type);
        $courseCat->price = intval($price);
        $courseCat->link = $link;

        // 图片
//        $courseCat->img = '';
        if ($request->hasFile('img')) {
            $courseCat->img = Qnupload::upload($_FILES['img'], '', 'coursecat');
        }

        $courseCat->save();

        return Redirect::to('/admin/course/cat', 301);
    }

    /**
     * 套课删除
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function catDel(Request $request)
    {
        $id = $request->input('id');
        if (!$id) {
            return $this->ajaxError('参数错误');
        }

        $courseCat = CourseCat::find($id);
        if (!$courseCat) {
            return $this->ajaxError('课程不存在');
        }
        $courseCat->delete();

        return $this->ajaxMsg('删除成功');
    }


    /**
    * send sq template message
    */
    public function sendSQTemplateMessage(Request $request)
    {
        // check
        $courseId = $request->request->get('cid'); //can not null
        $notify_title = $request->request->get('notify_title'); //
        $notify_content = $request->request->get('notify_content'); //
        $notify_remark = $request->request->get('notify_remark');
        $notify_odate = $request->request->get('notify_odate');
        $notify_address = $request->request->get('notify_address');
        $inclass_status = $request->request->get('inclass_status'); // 1, 2, 3
        $user_shop = $request->request->get('user_shop'); // 1, 2, 3

        if( empty($courseId) ||
            empty($notify_title) ||
            empty($notify_content) ||
            empty($notify_remark) ||
            empty($notify_odate ) ||
            empty($notify_address) ||
            ($inclass_status != 1 && $inclass_status != 2 && $inclass_status != 3) ||
            ($user_shop != 1 && $user_shop != 2 && $user_shop != 3)
        ){
            return response()->json(['status' => 0, 'msg' => '参数null or mis']);
        }

        $notify_url = $request->request->has('notify_url') ? $request->request->get('notify_url') : '';

        $course = Course::where('id', $courseId)->first();
        $course->notify_title = $notify_title;
        $course->notify_content = $notify_content;
        $course->notify_remark = $notify_remark;
        $course->notify_odate = $notify_odate;
        $course->notify_address = $notify_address;
        $course->notify_url = $notify_url;
        $course->save();

        //generating openid
        $openidsStr = $request->request->get('openids');
        if(!empty($openidsStr)){
            $openids = explode("\n", $openidsStr);
            $t = [];
            foreach($openids as $key => $openid){
                if(empty(trim($openid))){
                    unset($openids[$key]);
                }
            }
            if (count($openids) > 3000) {
                return $this->ajaxError('单次只能发送3000条');
            }
            $params = [
                'openids' => $openids,
                'cid' => $courseId
            ];
            $this->dispatch(new CreateSQTplmsgByOpenid($params));

        } else {
            $params = [
                'cid' => $course->id,
                'user_shop' => $user_shop,
                'inclass_status' => $inclass_status
            ];
            $this->dispatch(new CreateSQTplmsgFromCourse($params));
        }

        return $this->ajaxMsg('发送成功');
    }

    function detail ($id = 0)
    {
        $user_info = Session::get('admin_info');
        $id = intval($id);

        if (!$id) {
            return view('admin.error', ['msg' => '数据不存在']);
        }
        $course = Course::where("id", $id)->first();
        if (!$course) {
            return view('admin.error', ['msg' => '数据不存在']);
        }

        $endTime = "{$course->start_day} {$course->end_time}";
        $endTime = strtotime($endTime);
        $nowTime = time();
        $isEnd = ($nowTime >= $endTime) ;

        $liveService = new LiveService();
        $vodLists = $liveService->getVodList("wyeth_{$id}");
        $vodLists = array_reverse($vodLists);
        $course->realnum = CounterService::courseRegAllGet($id);

        $qcloud = new QcloudService;
        $qcloudVodList = $qcloud->getVodList($id);

        $data = [
            'course' => $course,
            'vodLists' => $vodLists,
            'isEnd' => $isEnd,
            'qcloudVodList' => $qcloudVodList,
        ];
        return view('admin.course.detail', $data)
            ->nest('header', 'admin.common.header', ['user_info'=>$user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu'=>Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    /**
     * recommend_course增加uid字段
     */
    public function addUidToRC()
    {
        $recommendCourses = RecommendCourse::get();
        foreach ($recommendCourses as $recommendCourse) {
            $user = User::where('openid', $recommendCourse->openid)->first();
            $recommendCourse = RecommendCourse::find($recommendCourse->id);
            $recommendCourse->uid = $user->id;
            $res = $recommendCourse->save();
        }
        if ($res) {
            echo 'success';
        }
    }
}
