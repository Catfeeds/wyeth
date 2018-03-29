<?php
namespace App\Services;

use App\Models\Teacher;
use Cache;
Use DB;
use App\Models\Course;
use App\Models\CourseReview;
use App\Models\UserCourse;
use App\Models\CourseTag;
use App\Models\Tag;
use App\Models\User;
use App\Models\CourseStat;
use App\Models\RecommendCourse;

class CourseService
{
    /**
     * 从缓存中获取某节课的报名人数
     * @param $cid
     * @return int
     */
    public static function reg($cid)
    {
        return CounterService::courseRegAllGet($cid);
    }

    /**
     * 从缓存中获取课程的热度
     * @param $cid
     * @return int
     */
    public static function hot($cid)
    {
        $nums = self::reg($cid);
        return $nums;
    }

    //查询推荐课程
    public static function recommendCourse($uid, $limit, $user_type, $recommend = true, $review = false)
    {
        //默认按照课程状态排序
        if ($review) {
            $courses = Course::where('status', '=', '3')
                ->orderBy(DB::raw('rand()'))
                ->limit($limit)
                ->get();
        } else if ($recommend) {
            $courses = Course::where('status', '<', '3')
                ->where('display_status', 1)
                ->whereIn('user_type', [0, $user_type])
                ->where('id', '<>', '40')
                ->orderBy('status', 'desc')
                ->orderBy('start_day', 'asc')
                ->limit($limit)
                ->get();
        } else {
            $courses = Course::where('display_status', 1)
                ->whereIn('user_type', [0, $user_type])
                ->where('id', '<>', '40')
                ->orderBy('start_day', 'desc')
                ->limit($limit)
                ->get();
        }

        //todo 按照用户行为进行推荐

        $data = self::formatCourseList($uid, $courses, $recommend);

        return $data;
    }

    //转换课程课程列表
    public static function formatCourseList($uid, $courses, $recommend)
    {
        $data = [];
        if (empty($courses)) {
            return $data;
        }

        //用户报名信息
        $cids = UserCourse::where('uid', $uid)->lists('cid')->toArray();

        //课程信息
        $course_ids = [];
        foreach ($courses as $row) {
            $course_ids[] = $row->id;
            $data[] = [
                'cid' => $row->id,
                'title' => $row->title,
                'img' => $row->img,
                'start_day' => $row->start_day,
                'start_time' => date("H:i", strtotime($row->start_time)),
                'end_time' => date("H:i", strtotime($row->end_time)),
                'teacher_name' => $row->teacher_name,
                'teacher_avatar' => $row->teacher_avatar,
                'teacher_hospital' => $row->teacher_hospital,
                'teacher_position' => $row->teacher_position,
                'hot' => $row->hot,
                'status' => $row->status,
                'is_signed' => empty($cids) ? 0 : in_array($row->id, $cids) ? 1 : 0,
                'notify_url' => $row->notify_url,
            ];
        }
        // 报名列表页的再按时间asc排
        if (!$recommend) {
            $grouped = collect($data)->groupBy('status');
            $dataLiving = $grouped->get(2) ?: collect([]);
            $dataLiving = $dataLiving->sortBy('start_day');
            $dataReg = $grouped->get(1) ?: collect([]);
            $dataReg = $dataReg->sortByDesc('start_day');
            $dataReview = $grouped->get(3) ?: collect([]);
            $dataReview = $dataReview->sortByDesc('start_day');
            $data = $dataLiving
                ->merge($dataReg)
                ->merge($dataReview);
        }

        return $data;
    }

    //上传opensearch文档数据
    public static function addSearchDoc($cid)
    {
        $course = Course::find($cid);
        if ($course) {
            $client = new Search();
            $doc_obj = $client->getCLientDoc();
            $docs_to_upload = array();

            // 指定文档操作类型为：添加
            $item['cmd'] = 'ADD';
            // 添加文档内容
            $item["fields"] = array(
                "id" => $course->id,
                "title" => $course->title,
                "number" => $course->number,
                "start_day" => $course->start_day,
                "course_desc" => $course->desc,
                "stage" => $course->stage,
                "teacher_name" => $course->teacher_name,
                "teacher_hospital" => $course->teacher_hospital,
                "teacher_position" => $course->teacher_position,
                "teacher_desc" => $course->teacher_desc,
                "status" => $course->status,
                "area_city_id" => $course->area_city_id,
            );
            $docs_to_upload[] = $item;

            // 生成json格式字符串
            $json = json_encode($docs_to_upload);
            // 将文档推送到main表中
            return $doc_obj->add($json, "main");
        }
    }

    //修改opensearch文档数据
    public static function updateSearchDoc($cid)
    {
        $course = Course::find($cid);
        if ($course) {
            $client = new Search();
            $doc_obj = $client->getCLientDoc();
            $docs_to_upload = array();

            // 指定文档操作类型为：修改
            $item['cmd'] = 'UPDATE';
            // 添加文档内容
            $item["fields"] = array(
                "id" => $course->id,
                "title" => $course->title,
                "number" => $course->number,
                "start_day" => $course->start_day,
                "course_desc" => $course->desc,
                "stage" => $course->stage,
                "teacher_name" => $course->teacher_name,
                "teacher_hospital" => $course->teacher_hospital,
                "teacher_position" => $course->teacher_position,
                "teacher_desc" => $course->teacher_desc,
                "status" => $course->status,
                "area_city_id" => $course->area_city_id,
            );
            $docs_to_upload[] = $item;

            // 生成json格式字符串
            $json = json_encode($docs_to_upload);
            // 将文档推送到main表中
            return $doc_obj->update($json, "main");
        }
    }

    /**
     * 首页页面通过输入指定课程id,读取数据
     *
     * @param  int $uid
     * @param  array $ids
     *
     * @return array
     */
    public static function courseRecommend($uid, $ids)
    {
        $courses = Course::whereIn('id', $ids)
            //因为首页要显示回顾的课程，所以去掉3条件
            //->where('status', '<', '3')
            ->where('display_status', 1)
            ->get();

        $data = self::attachedToDynamicData($courses, $uid);

        $result = [];
        foreach ($ids as $id) {
            $id = intval($id);
            $filtered = collect($data)->where('cid', $id);
            $result[] = collect($data)->where('cid', $id)->first();
        }

        return $result;
    }

    /**
     * 从数据库取出的course数据段附加上相关的course_user等字段数据输出
     *
     * @param Illuminate\Support\Collection $courses
     * @param int $uid
     *
     * @return arr
     */
    public static function attachedToDynamicData($courses, $uid)
    {
        $data = [];
        if (empty($courses)) {
            return $data;
        }

        //用户报名信息
        $courseIds = collect($courses)->pluck('id');
        $userCourseCids = UserCourse::forceIndex('user_course_uid_index')
            ->where('uid', $uid)->wherein('cid', $courseIds)->lists('cid')->toArray();

        //课程信息
        foreach ($courses as $row) {
            $isSigned = 0;
            if (!empty($userCourseCids) && in_array($row->id, $userCourseCids)) {
                $isSigned = 1;
            }
            if ($row->status == Course::COURSE_LIVING_STATUS) {
                if ($isSigned) {
                    $url = "/mobile/living?cid={$row->id}";
                } else {
                    $url = "/mobile/reg?cid={$row->id}";
                }
            } elseif ($row->status == Course::COURSE_END_STATUS) {
                $url = "/mobile/end?cid={$row->id}";
            } else {
                $url = "/mobile/reg?cid={$row->id}";
            }

            //标签
            $tagsUntreated = $row->tags;
            $tags = [];
            foreach ($tagsUntreated as $tag) {
                if ($tag->type == 0)  {
                    $tags[] = $tag->name;
                }
            }
            $tags = array_slice($tags, 0, 2);

            //报名数
            $signNum = self::reg($row->id);

            /* 优先级别
                02 直播中
                03 回顾
                //04 已报名
                01 报名中
            */
            $data[] = [
                'cid' => $row->id,
                'title' => $row->title,
                'img' => $row->img,
                'start_day' => $row->start_day,
                'start_time' => date("H:i", strtotime($row->start_time)),
                'end_time' => date("H:i", strtotime($row->end_time)),
                'teacher_name' => $row->teacher_name,
                'teacher_avatar' => $row->teacher_avatar,
                'teacher_hospital' => $row->teacher_hospital,
                'teacher_position' => $row->teacher_position,
                'hot' => $row->hot,
                'status' => ($row->status == 1 && $isSigned) ? 4 : $row->status,
                'is_signed' => $isSigned,
                'notify_url' => $row->notify_url,
                'url' => $url,
                'tags' => $tags,
                'signNum' => $signNum,
            ];
        }
        return $data;
    }

    /**
     * 为index all droplist 页面，分析传过来的参数从数据库中读出course表的数据
     * 注： 因为
     *
     * 排序规则
     * new :
     *     status 213
     *     number
     *
     * hot :
     *     status 213
     *     hot
     *
     * review :
     *     status = 3
     *     number
     *
     * @param  int $uid
     * @param  int $userType
     * @param  int $page
     * @param  string $type
     * @param  int $number 每次读取几条数据
     * @param  int $stage
     * @param  string $tag
     *
     * @return arr
     */
    public static function getDynamicData($uid, $userType = 1, $page = 1, $type = 'new', $number = 6, $stage = 0, $tagId = false)
    {
        $page--;
        $offset = $page * $number;

        $query = Course::where('course.display_status', 1);
        $query->whereIn('course.user_type', [0, $userType]);
        $query->take($number);
        $query->offset($offset);
        switch ($type) {
            case 'new':
                $query->orderBy(DB::raw('field(status,' . Course::COURSE_STATUS_ORDER . ')'));
                //$query->orderBy('number', 'desc');
                $query->orderBy('course.start_day', 'desc');
                $query->orderBy('course.start_time', 'desc');
                break;
            case 'hot':
                $query->orderBy(DB::raw('field(status,' . Course::COURSE_STATUS_ORDER . ')'));
                $query->orderBy('course.hot', 'desc');
                break;
            case 'review':
                $query->where('course.status', '=', 3);
                // $query->join(DB::raw("right join course_review as r ON r.cid=course.id AND r.status=".CourseReview::STATUS_YES));
                $query->rightJoin('course_review as r', DB::raw('r.cid'), '=', 'course.id');
                $query->whereRaw('r.status=' . CourseReview::STATUS_YES);
                $query->orderBy('course.start_day', 'desc');
                $query->orderBy('course.start_time', 'desc');
                break;
        }

        /**
         * stage 1孕早期  2孕晚期  3新手妈咪
         * 孕早期 0-3个月
         * 孕晚期 4-10个月
         */
        if ($stage > 0) {
            if($stage == 1){
                //早期
//                $query->where('course.stage_from','<', 203);
//                $query->where('course.stage_from','>', 100);
                //  新版孕期
                $query->where('course.stage_from','<', 211);
                $query->where('course.stage_from','>', 99);
            }else if($stage == 2){
                //中晚期
//                $query->whereBetween('course.stage_to', [203, 210]);
//                $query->orWhere(function ($query) {
//                    $query->whereBetween('course.stage_from', [203, 210]);
//                });
//                $query->orWhere(function ($query) {
//                    $query->where('course.stage_to', '<', 203);
//                    $query->where('course.stage_from', '>', 210);
//                  });
                // 新版0-12月
                $query->whereBetween('course.stage_to', [300, 301]);
                $query->orWhere(function ($query) {
                    $query->whereBetween('course.stage_from', [300, 301]);
                });
                $query->orWhere(function ($query) {
                    $query->where('course.stage_to', '<', 301);
                    $query->where('course.stage_from', '>', 300);
                });
            }else if($stage == 3){
                //宝宝
//                $query->where('course.stage_to', '>=', 301);
//                $query->where('course.stage_from', '>=', 100);
                // 新版 12-24月
                $query->whereBetween('course.stage_to', [301, 302]);
                $query->orWhere(function ($query) {
                    $query->whereBetween('course.stage_from', [301, 302]);
                });
                $query->orWhere(function ($query) {
                    $query->where('course.stage_to', '<', 302);
                    $query->where('course.stage_from', '>', 301);

                });
            } else if ($stage == 4) {
                $query->where('course.stage_to', '>=', 303);
                $query->where('course.stage_from', '>=', 100);
            }

        }

        //通过tag_id 取出所有对应的cid,然后in
        if ($tagId != false) {
            $courseTags = CourseTag::where('tid', $tagId)->get();
            $tagIds = [];
            foreach ($courseTags as $courseTag) {
                $tagIds[] = $courseTag['cid'];
            }
            $query->whereIn('course.id', $tagIds);
        }

        $query->where('course.id', '<>', 40);

        $courses = $query->select('course.*')->get();
        $data = self::attachedToDynamicData($courses, $uid);
        return $data;
    }


    /**
     * 功能：  报名成功页课程推荐
     *
     * @param  int     $uid       用户id
     * @param  int     $userType  用户类型         0 全部用户  1 微信用户  2 手Q用户
     * @param  string  $type      课程类型         review 回顾   unsigned 报名中
     * @param  int     $number    每次读取几条数据
     * @param  int     $cid       用户报名的课程id
     * @return arr
     */
    public static function signOkCoursesRecommended($uid, $userType, $type, $number, $cid)
    {
        $page = 1;
        $page--;
        $offset = $page * $number;

        $query = Course::where('course.display_status', 1);
        $query->whereIn('course.user_type', [0, $userType]);
        $query->take($number);
        $query->offset($offset);

        //通过课程类型查找课程  review 回顾  unsigned 报名中  unsignreview 未报名回顾
        switch ($type) {
            case 'review':
                $query->where('course.status', '=', 3);
                $query->rightJoin('course_review as r', DB::raw('r.cid'), '=', 'course.id');
                $query->whereRaw('r.status=' . CourseReview::STATUS_YES);
                $query->orderBy('course.start_day', 'desc');
                $query->orderBy('course.start_time', 'desc');
                break;
            case 'unsigned':
                $userCourseCids = UserCourse::where('uid', $uid)->lists('cid')->toArray();
                $query->whereNotIn('course.id',$userCourseCids);
                if ($cid) {
                    $query->where('course.id', '<>', $cid);
                }
                $query->where('course.status', '=', 1);
                $query->orderBy('course.start_day', 'asc');
                $query->orderBy('course.start_time', 'asc');
                break;
            case 'unsignreview':
                $userCourseCids = UserCourse::where('uid', $uid)->lists('cid')->toArray();
                $query->whereNotIn('r.cid', $userCourseCids);
                $query->where('course.status', 3);
                $query->rightJoin('course_review as r', DB::raw('r.cid'), '=', 'course.id');
                $query->whereRaw('r.status=' . CourseReview::STATUS_YES);
                $query->orderBy('course.start_day', 'desc');
                $query->orderBy('course.start_time', 'desc');
                break;
        }

        /**
         * 通过用户阶段查找课程
         * stage 0获取不到宝宝生日 1孕早期  2孕晚期  3新手妈咪
         * 孕早期 0-3个月
         * 孕晚期 4-10个月
         */
        $user = User::find($uid);
        $babyBirthday = $user->baby_birthday;
        if($babyBirthday) {
            $babyBirthday = strtotime($babyBirthday);
        }
        $now = time();
        if (!$babyBirthday) {
            $stage = 0;
        } else if ($babyBirthday <= $now) {
            $stage = 3;
        } else if (($babyBirthday - $now) > 60*60*24*30*7) {
            $stage = 1;
        } else {
            $stage = 2;
        }

        if ($stage > 0) {
            if($stage == 1){
                //早期
                $query->where('course.stage_from','<', 203);
                $query->where('course.stage_from','>', 100);
            }else if($stage == 2){
                //中晚期
                $query->whereBetween('course.stage_to', [203, 210]);
                $query->orWhere(function ($query) {
                    $query->whereBetween('course.stage_from', [203, 210]);
                });
                $query->orWhere(function ($query) {
                    $query->where('course.stage_to', '<', 203);
                    $query->where('course.stage_from', '>', 210);
                });
            }else if($stage == 3){
                //宝宝
                $query->where('course.stage_to', '>=', 300);
                $query->where('course.stage_from', '>=', 100);
            }
        }
        //id为40这节课是测试课程，要排除掉
        $query->where('course.id', '<>', 40);
        $courses = $query->select('course.*')->get();
        $data = self::attachedToDynamicData($courses, $uid);
        return $data;
    }

    /**
     * 功能：  回顾页课程推荐
     *
     * @param  int     $uid       用户id
     * @param  int     $userType  用户类型         0 全部用户  1 微信用户  2 手Q用户
     * @param  string  $type      课程类型         review 回顾   unsigned 报名中
     * @param  int     $number    每次读取几条数据
     * @param  int     $cid       用户报名的课程id
     * @param  bool    $isStage   是否同阶段
     * @param  arr     $notInCid  不包含的课程cid
     * @return arr
     */
    public static function endCoursesRecommended($uid, $userType, $type, $number, $cid, $isStage = true, $notInCid = [])
    {
        $page = 1;
        $page--;
        $offset = $page * $number;

        $query = Course::where('course.display_status', 1);
        $query->whereIn('course.user_type', [0, $userType]);
        $query->take($number);
        $query->offset($offset);
        if ($notInCid) {
            $query->whereNotIn('course.id',$notInCid);
        }
        if ($cid) {
            $query->where('course.id', '<>', $cid);
        }
        //通过课程类型查找课程  review 回顾  unsigned 报名中
        switch ($type) {
            case 'review':
                $query->where('course.status', '=', 3);
                $query->rightJoin('course_review as r', DB::raw('r.cid'), '=', 'course.id');
                $query->whereRaw('r.status=' . CourseReview::STATUS_YES);
                $query->orderBy('course.start_day', 'desc');
                $query->orderBy('course.start_time', 'desc');
                break;
            case 'unsigned':
                $userCourseCids = UserCourse::where('uid', $uid)->lists('cid')->toArray();
                $query->whereNotIn('course.id',$userCourseCids);
                $query->where('course.status', '=', 1);
                $query->orderBy('course.start_day', 'asc');
                $query->orderBy('course.start_time', 'asc');
                break;
        }

        /**
         * 通过用户阶段查找课程
         * stage 0获取不到宝宝生日 1孕早期  2孕晚期  3新手妈咪
         * 孕早期 0-3个月
         * 孕晚期 4-10个月
         */
        $user = User::find($uid);
        $babyBirthday = $user->baby_birthday;
        if($babyBirthday) {
            $babyBirthday = strtotime($babyBirthday);
        }
        $now = time();
        if (!$babyBirthday) {
            $stage = 0;
        } else if ($babyBirthday <= $now) {
            $stage = 3;
        } else if (($babyBirthday - $now) > 60*60*24*30*7) {
            $stage = 1;
        } else {
            $stage = 2;
        }
        if ($isStage == true && $stage > 0) {
            if($stage == 1){
                //早期
                $query->where('course.stage_from','<', 203);
                $query->where('course.stage_from','>', 100);
            }else if($stage == 2){
                //中晚期
                $query->where(function($query) {
                    $query->whereBetween('course.stage_to', [203, 210]);
                    $query->orWhere(function ($query) {
                        $query->whereBetween('course.stage_from', [203, 210]);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('course.stage_to', '<', 203);
                        $query->where('course.stage_from', '>', 210);
                    });
                });
            }else if($stage == 3){
                //宝宝
                $query->where('course.stage_to', '>=', 300);
                $query->where('course.stage_from', '>=', 100);
            }
        }
        //id为40这节课是测试课程，要排除掉
        $query->where('course.id', '<>', 40);
        $courses = $query->select('course.*')->get();
        $data = self::attachedToDynamicData($courses, $uid);
        return $data;
    }


    /*
     * 随机推荐课程
     */
    public static function recommendCourseIdGet($user, $signUpCourse)
    {
        if (!$signUpCourse) {
            return false;
        }
        $stageFrom = $signUpCourse->stage_from;
        $stageTo = $signUpCourse->stage_to;
        $courseReviewIds = CourseReview::lists('cid')->toArray();
        $status = 0;
        if ($stageFrom >= 100 && $stageTo <= 200) {
            $stage = '备孕';
            $status = 1;
            $stageFrom = 100;
            $stageTo = 300;
        }
        if ($stageFrom >= 200 && $stageTo <= 300) {
            $stage = '孕期';
            $status = 2;
            $stageFrom = 200;
            $stageTo = 300;
        }
        if ($stageFrom >= 300 && $stageTo <= 400) {
            $stage = '新生儿';
            $status = 3;
            $stageFrom = 300;
            $stageTo = 400;
        }
        if ($status == 0 && $user->baby_birthday !== null) {
            if ($user->baby_birthday > date("Y-m-d H:i:s")) {
                $stageFrom = 200;
                $stageTo = 300;
                $stage = '孕期';
            } else {
                $stageFrom = 300;
                $stageTo = 400;
                $stage = '新生儿';
            }
        }
        $stage = !empty($stage) ? $stage : '新生儿';
        $startDate = date('Y-m-d 00:00:00');
        $deadDate = date('Y-m-d 23:59:59');
        $notInIds = RecommendCourse::where('uid', $user->id)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $deadDate)
            ->lists('recommend_course_id')
            ->toArray();

        $courses = Course::where('stage_from', '>=', $stageFrom)
            ->where('stage_to', '<', $stageTo)
            ->where('display_status', 1)
            ->whereIn('id', $courseReviewIds)
            ->whereNotIn('id', $notInIds)
            ->get();
        $courseIds = [];
        foreach ($courses as $course) {
            $courseIds[] = $course->id;
        }
        $cids = CourseStat::where('in_class_time', '0000-00-00 00:00:00')
            ->where('in_review_time', '0000-00-00 00:00:00')
            ->where('uid', $user->id)
            ->whereIn('cid', $courseIds)
            ->lists('cid')->toArray();
        $recommendCourse = '';
        $number = 0;
        if ($cids) {
            $cid = $cids[array_rand($cids)];
            $recommendCourse = Course::find($cid);
        } else {
            $number = rand(0, count($courses)-1);
            foreach ($courses as $key => $value) {
                if ($key == $number) {
                    $recommendCourse = $value;
                }
            }
        }

        if (!$recommendCourse) {
            return false;
        }
        $content = "《" . $recommendCourse->title . "》";
        // 妈妈状态
        switch ($stage) {
            case '备孕':
                $customArea = [
                    '1' => '      当妈妈之前应该做好哪些准备呢？如何成功怀上健康的精英宝宝？备孕冷知识，你都知道吗？',
                    '2' => '看过来！［妈妈微课堂］教你备孕小技巧～'
                ];
                break;
            case '孕期':
                $customArea = [
                    '1' => '      随着胎宝宝一天一天长大，你知道他最需要什么营养吗？怎么和他交流？孕期哪些检查是必须做的？',
                    '2' => '点击进入，答案就在［妈妈微课堂］，告别新手妈咪，干货知识全给你！'
                ];
                break;
            case '新生儿':
                $customArea = [
                    '1' => '      你知道月子应该怎么坐？母乳不够怎么办？宝宝腹泻如何处理？早教应该从何下手？辅食添加有哪些禁忌？',
                    '2' => '别担心，点这里！专家解答全在［妈妈微课堂］！从此带娃so easy！'
                ];
                break;
        }
        //发送模版消息
        $params = [
            'tpl_params' => [
                'openid' => $user->openid,
                'title' => "亲爱的妈妈：" . "\n" . '      恭喜你报名成功！' ."\n" . "\n" . $customArea[1],
                'content' => $content ,
                'odate' => date("Y年m月d日", strtotime($recommendCourse->start_day)) . " " . date("H:i", strtotime($recommendCourse->start_time)),
                'address' => '妈妈微课堂' . "\n",
                'remark' => '      ' . $customArea[2],
                'url' => config('app.url') . '/mobile/end?cid=' . $recommendCourse->id . '&_hw_c=wxtpl_tjhg' . $recommendCourse->id,
            ],
            'need_check' => false,
            'template_id' => 2,
            'sign_up_course' => $signUpCourse,
            'recommend_course' => $recommendCourse,
            'uid' => $user->id
        ];
        return $params;
    }


    /**
     * 根据用户月龄和品牌推荐相关的一节课,暂用于模板消息自动推送
     * @param $user
     */
    public static function recommendCourseByUser($uid){

        //显示的课,有通知内容的,忽略当天要上课的
        $query = Course::where('course.display_status', 1)
            ->where('notify_title', '>', '')
            ->where('start_day', '<>', date('Y-m-d'));

        //忽略一个月内推荐过的
        $startDate = date('Y-m-d', strtotime('-1 month'));
        $notInIds = RecommendCourse::where('uid', $uid)
            ->where('created_at', '>=', $startDate)
            ->lists('recommend_course_id')
            ->toArray();
        if ($notInIds) {
            $query->whereNotIn('course.id',$notInIds);
        }

        //找有回顾的
        $query->where('course.status', '=', 3);
        $query->rightJoin('course_review as r', DB::raw('r.cid'), '=', 'course.id');
        $query->whereRaw('r.status=' . CourseReview::STATUS_YES);
        $query->orderBy('course.start_day', 'desc');
        $query->orderBy('course.start_time', 'desc');

        /**
         * 通过用户阶段查找课程
         * stage 0获取不到宝宝生日 1孕早期  2孕晚期  3新手妈咪
         * 孕早期 0-3个月
         * 孕晚期 4-10个月
         */
        $user = User::find($uid);
        if (!$user){
            return 0;
        }
        $babyBirthday = $user->baby_birthday;
        if($babyBirthday) {
            $babyBirthday = strtotime($babyBirthday);
        }
        $now = time();
        if (!$babyBirthday) {
            $stage = 0;
        } else if ($babyBirthday <= $now) {
            $stage = 3;
        } else if (($babyBirthday - $now) > 60*60*24*30*7) {
            $stage = 1;
        } else {
            $stage = 2;
        }
        if ($stage > 0) {
            if($stage == 1){
                //早期
                $query->where('course.stage_from','<', 203);
                $query->where('course.stage_from','>', 100);
            }else if($stage == 2){
                //中晚期
                $query->where(function($query) {
                    $query->whereBetween('course.stage_to', [203, 210]);
                    $query->orWhere(function ($query) {
                        $query->whereBetween('course.stage_from', [203, 210]);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('course.stage_to', '<', 203);
                        $query->where('course.stage_from', '>', 210);
                    });
                });
            }else if($stage == 3){
                //宝宝
                $query->where('course.stage_to', '>=', 300);
                $query->where('course.stage_from', '>=', 100);
            }
        }

        //匹配品牌,没有匹配到就不匹配
        $user_brand = (new Crm())->getMemberBrand($user->openid);
        //暂时把无品牌的匹配成金装的推
        if ($user_brand <= 1) {
            $user_brand = 4;
        }
        $clone_query = clone $query;
        $course = $clone_query->where('course.brand', $user_brand)->select('course.*')->first();
        if (!$course){
            $course = $query->select('course.*')->first();
        }
        if (!$course){
            return 0;
        }
        return $course->id;
    }

    //根据用户品牌获取推荐课
    public static function recommendCourseByBrand($brand)
    {

        $query = Course::where('course.display_status', 1)
            ->where('notify_title', '>', '')
            ->where('course.status', 3)
            ->where('brand', $brand);
        $query->rightJoin('course_review as r', DB::raw('r.cid'), '=', 'course.id');
        $query->whereRaw('r.status=' . CourseReview::STATUS_YES);
        $query->orderBy('course.start_day', 'desc');
        $query->orderBy('course.start_time', 'desc');

        $courses = $query->select('course.id')->get()->pluck('id')->toArray();
        return $courses;
    }

    public static function getCourseInfoById($uid, $cid){
        $img_suffix = '?imageView2/1/w/220/h/220';
        $course = Course::where('id', $cid)->first();
        $data = [];
        if($course != NULL){
            $userCourseCids = UserCourse::forceIndex('user_course_uid_index')
                ->where('uid', $uid)->where('cid', $cid)->get();
            $isSigned = 0;
            if ($userCourseCids != NULL) {
                $isSigned = 1;
            }
            if ($course->status == Course::COURSE_LIVING_STATUS) {
                if ($isSigned) {
                    $url = "/mobile/living?cid={$course->id}";
                } else {
                    $url = "/mobile/reg?cid={$course->id}";
                }
            } elseif ($course->status == Course::COURSE_END_STATUS) {
                $url = "/mobile/end?cid={$course->id}";
            } else {
                $url = "/mobile/reg?cid={$course->id}";
            }

            $courseReview = CourseReview::where('cid', $course->id)->first();
            $review_type = 0;
            if($courseReview){
                $review_type = $courseReview->review_type;
            }

            $course_tags = CourseTag::forceIndex('course_tags_cid_type_index')->where('cid', $course->id)->where('type', 3)->get()->toArray();
            $tagName = '';
            $name = [];
            foreach ($course_tags as $course_tag){
                $tag = Tag::where('id', $course_tag['tid'])->first();
                if($tagName == ''){
                    $tagName = $tag ? $tag->name : '';
                }
                if ($tag) {
                    $name[] = $tag->name;
                }
            }
            //报名数
            $signNum = CounterService::courseRegAllGet($cid);
            //teacher_id
            $teacher = Teacher::where('name', $course->teacher_name)->first();
            $data = [
                'id' => $course->id,
                'cid' => $course->cid,
                'title' => $course->title,
                'img' => $course->img . $img_suffix,
                'hot' => $signNum,
                'start_day' => $course->start_day,
                'start_time' => date("H:i", strtotime($course->start_time)),
                'end_time' => date("H:i", strtotime($course->end_time)),
                'teacher_id' => $teacher ? $teacher->id : 0,
                'teacher_name' => $course->teacher_name,
                'teacher_hospital' => $teacher ? $teacher->hospital : $course->teacher_hospital,
                'teacher_position' => $teacher ? $teacher->position : $course->teacher_position,
                'teacher_avatar' => $teacher ? $teacher->avatar : $course->teacher_avatar,
                'teacher_desc' => $teacher ? $teacher->desc : $course->teacher_desc,
                'review_type' => $review_type,
                'desc' => $course->desc,
                'status' => ($course->status == 1 && $isSigned) ? 4 : $course->status,
                'url' => $url,
                'brand' => $course->brand,
                'tags' => $name,
                'tag' => $tagName,
                'signNum' => $signNum,
                'notify_url' => $course->notify_url,
                'created_at' => $course->created_at
            ];
        }
        return $data;
    }

    public static function filterCourse($course){
        if(is_array($course)){
            if($course['teacher_name'] == '' || $course['display_status'] != 1){
                return false;
            }
            $now = strtotime(date('Y-m-d H:i:s'));
            $courseTime = strtotime($course['start_day'] . ' ' . $course['start_time']);
            $courseReview = CourseReview::where('cid', $course['id'])->first();
            if(!$courseReview && ($now > $courseTime)){
                return false;
            }
            //如果没有显示tag,那么也不予显示
            $courseTags = CourseTag::forceIndex('course_tags_cid_type_index')->where('cid', $course['id'])->where('type', 3)->get()->toArray();
            if(count($courseTags) > 0){
                return true;
            }else{
                return false;
            }
        }else{
            if($course && $course->id){
                if($course->teacher_name == '' || $course->display_status != 1){
                    return false;
                }
                $now = strtotime(date('Y-m-d H:i:s'));
                $courseTime = strtotime($course->start_day . ' ' . $course->start_time);
                $courseReview = CourseReview::where('cid', $course->id)->first();
                if(!$courseReview && ($now > $courseTime)){
                    return false;
                }
                //如果没有内容tag,那么也不予显示
                $courseTags = CourseTag::forceIndex('course_tags_cid_type_index')->where('cid', $course->id)->where('type', 3)->first();
                if($courseTags){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }
}
