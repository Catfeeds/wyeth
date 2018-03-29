<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Message;
use App\Services\CounterService;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Response;
class CourseExportController extends Controller
{

    public function index(Request $request)
    {
        $per_page = 10;
        $params = [];
        $params['title'] = $request->input('title');
        $params['number'] = $request->input('number');
        $params['sort'] = $request->input('sort');
        $params['id'] = $request->input('id');

        if (!empty($params['id']) || $params['id'] === '0') {
            $list = DB::table('course')->where("id", "=", $params['id']);
        } else {
            $list = DB::table('course')->where("id", ">", 0);
        }
        !empty($params['title']) && $list->where("title", "like", "%" . $params['title'] . "%");
        if (!empty($params['number']) || $params['number'] === '0') {
            $list->where("number", "=", $params['number']);
        }
        $list = $list->orderBy('id', 'desc')->paginate($per_page);
        $data = [
            'list' => $list,
            'params' => $params,
        ];
        return view('admin.course.export', $data)
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function export(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        if (empty($id) || empty($type)) {
            return view('admin.error', ['msg' => '课程id和导出类型不能为空']);
        }

        $filename = "{$id}_result_{$type}.csv";
        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0'
            , 'Content-type' => 'text/csv'
            , 'Content-Disposition' => 'attachment; filename=' . $filename
            , 'Expires' => '0'
            , 'Pragma' => 'public',
        ];
        if ($type == 'sign_up') {
            $columnNames = [
                '课程id',
                '用户openid',
                '来源',
                '有主',
                '性别',
                '微信国家',
                '微信省份',
                '微信城市',
                '惠氏省份',
                '惠氏城市',
                '手机号码',
                '报名时间',
                '预产期或宝宝生日',
            ];
            $query = DB::table('user_course')
                ->leftJoin('user', 'user.id', '=', 'user_course.uid')
                ->where('user_course.cid', $id)
                ->select('user_course.id', 'user_course.cid', 'user.openid', 'user_course.channel', 'user.crm_hasShop', 'user.sex', 'user.country', 'user.province', 'user.city', 'user.crm_province', 'user.crm_city', 'user.mobile', 'user_course.created_at', 'user.baby_birthday')
                ->orderBy('user_course.id', 'asc');
            $whereId = 'user_course.id';
            return $this->handle($headers, $columnNames, $query, $whereId);
        } else if ($type == 'course_info') {
            $columnNames = [
                '课程id',
                '用户openid',
                '首次进入直播间的时间',
                '首次进入的link里的来源',
                '设备',
            ];
            $query = DB::table('course_stat')
                ->where('course_stat.cid', $id)
                ->where('course_stat.listen_time', '>', 0)
                ->join('user', 'course_stat.uid', '=', 'user.id')
                ->select('course_stat.id', 'course_stat.cid', 'user.openid', 'course_stat.in_class_time', 'course_stat.channel', 'course_stat.device')
                ->orderBy('course_stat.id', 'asc');
            $whereId = 'course_stat.id';
            return $this->handle($headers, $columnNames, $query, $whereId);
        } else if ($type == 'live_act') {
            $columnNames = [
                '课程id',
                '用户openid',
                '行为名称',
                '行为时间',
            ];
            $query = DB::table('user_events')
                ->where('user_events.cid', $id)
                ->join('user', 'user_events.uid', '=', 'user.id')
                ->select('user_events.id', 'user_events.cid', 'user.openid', 'user_events.type', 'user_events.created_at')
                ->orderBy('user_events.id', 'asc');
            $whereId = 'user_events.id';
            return $this->handle($headers, $columnNames, $query, $whereId);

        } else if ($type == 'msg_push_log') {
            $columnNames = [
                '课程id',
                '推送openid',
                '推送时间',
                '推送结果',
                '结果Code',
                'type',
            ];
            $query = DB::table('tplmsgs')
                ->where('cid', $id)
                // ->where('type', 1)
                ->select('tplmsgs.id', 'cid', 'openid', 'created_at', 'status', 'code', 'type')
                ->orderBy('tplmsgs.id', 'asc');
            $whereId = 'tplmsgs.id';
            return $this->handle($headers, $columnNames, $query, $whereId);
        } else if ($type == 'questions_record') {
            $columnNames = [
                '课程id',
                '在提问区发消息的openid',
                '发送时间',
                '发送内容',
                '讲师是否回答',
                '主持人提交时间',
                '回答时间',
            ];
            $query = DB::table('message')
                ->where('message.cid', $id)
                ->where('message.type', Message::TYPE_TEXT)
                ->whereIn('message.state', Message::$STATE_QUESTIONS)
                ->where('message.display', Message::DISPLAY_YES)
                ->join('user', 'message.author_id', '=', 'user.id')
                ->select('message.id', 'message.cid', 'user.openid', 'message.created_at', 'message.content', 'message.state', 'message.submit_time', 'message.answer_time')
                ->orderBy('message.id', 'asc');
            $whereId = 'message.id';
            return $this->handle($headers, $columnNames, $query, $whereId);
        } else if ($type == 'chat_record') {
            $columnNames = [
                '课程id',
                '在讨论区发消息的openid',
                '发送时间',
                '发送内容',
            ];
            $query = DB::table('message')
                ->where('message.cid', $id)
                ->where('message.type', Message::TYPE_TEXT)
                ->where('message.state', Message::STATE_CHAT)
                ->where('message.display', Message::DISPLAY_YES)
                ->join('user', 'message.author_id', '=', 'user.id')
                ->select('message.id', 'message.cid', 'user.openid', 'message.created_at', 'message.content')
                ->orderBy('message.id', 'asc');
            $whereId = 'message.id';
            return $this->handle($headers, $columnNames, $query, $whereId);
        } else if ($type == 'flower_record') {
            $columnNames = [
                '课程id',
                '献花的openid',
                '献花时间',
            ];
            $query = DB::table('message')
                ->where('message.cid', $id)
                ->where('message.type', Message::TYPE_PRESENT_FLOWER)
                ->where('message.display', Message::DISPLAY_YES)
                ->join('user', 'message.author_id', '=', 'user.id')
                ->select('message.id', 'message.cid', 'user.openid', 'message.created_at')
                ->orderBy('message.id', 'asc');
            $whereId = 'message.id';
            return $this->handle($headers, $columnNames, $query, $whereId);
        } else if ($type == 'sign_up_stat') {
            $columnNames = [
                '课程id',
                '日期',
                '人数',
            ];
            $callback = function () use ($headers, $columnNames, $id) {
                $fileHandle = fopen('php://output', 'w');
                $this->str_putcsv($fileHandle, $columnNames);
                $course = Course::find($id);
                if (!$course) {
                    return '';
                }
                $createdDay = Carbon::parse($course->created_at)->startOfDay();
                $startDay = Carbon::parse($course->start_day)->startOfDay();
                while ($createdDay->diffInDays($startDay, false) >= 0) {
                    $day = $createdDay->toDateString();
                    $item = [
                        $id,
                        $day,
                        CounterService::courseRegDayGet($id, $day)
                    ];
                    $this->str_putcsv($fileHandle, $item);
                    $createdDay->addDay();
                }
                fclose($fileHandle);
            };
            return Response::stream($callback, 200, $headers);

        } else if ($type == 'course_stat') {
            $columnNames = [
                '课程id',
                'openid',
                '报名时间',
                '是否分享报名页',
                '分享报名页点击次数',
                '进入课堂时间',
                '最后离开课堂时间',
                '进入课堂次数',
                '听课时长',
                '是否分享直播页',
                '分享直播页点击次数',
                '来源设备',
                '发言次数',
                '讲师回答次数',
                '主持人回答次数',
                '是否分享回顾页',
                '分享回顾页点击次数',
                '最后进入回顾页面时间',
                '最后打开回顾页面时间',
            ];
            $query = DB::table('course_stat')->join('user', 'user.id', '=', 'course_stat.uid')
                ->where('course_stat.cid', $id)
                ->select('course_stat.id', 'course_stat.cid', 'user.openid', 'course_stat.sign_time', 'course_stat.share_sign_page', 'course_stat.share_sign_page_clicks', 'course_stat.in_class_time', 'course_stat.out_class_time', 'course_stat.in_class_times', 'course_stat.listen_time', 'course_stat.share_living_page', 'course_stat.share_living_page_clicks', 'course_stat.device', 'course_stat.speak_times', 'course_stat.teacher_answer_times', 'course_stat.anchor_answer_times', 'course_stat.share_review_page', 'course_stat.share_review_page_clicks', 'course_stat.in_review_time', 'course_stat.go_review_time')
                ->orderBy('course_stat.id', 'asc');
            $whereId = 'course_stat.id';
            return $this->handle($headers, $columnNames, $query, $whereId);
        } elseif ($type == 'course_rate') {
            $columnNames = [
                'id',
                '用户id',
                '课程id',
                '分数',
                '内容',
                '更新时间',
            ];
            $query = DB::table('estimation')
                ->leftJoin('user', 'user.id', '=', 'estimation.uid')
                ->where('estimation.cid', $id)
                ->select('estimation.id', 'user.openid', 'estimation.cid', 'estimation.mark', 'estimation.content', 'estimation.updated_at')
                ->orderBy('estimation.id', 'asc');
            $whereId = 'estimation.id';
            return $this->handle($headers, $columnNames, $query, $whereId, '>', false);
        } else if ($type == 'review_record') {
            $columnNames = [
                '课程id',
                '用户openid',
                '行为名称',
                '行为时间',
                '进入页面时长',
                '听课时长',
                '来源'
            ];
            $eventTypes = ['review_in', 'review_audio_begin', 'review_audio_pause', 'review_video_begin', 'review_video_pause'];
            $query = DB::table('user_events')
                ->where('user_events.cid', $id)
                ->whereIn('user_events.type', $eventTypes)
                ->join('user', 'user_events.uid', '=', 'user.id')
                ->select('user_events.id', 'user_events.cid', 'user.openid', 'user_events.type', 'user_events.created_at', 'user_events.data')
                ->orderBy('user_events.id', 'asc');

            $whereId = 'user_events.id';
            $jsonDecode = function ($item) {
                $dataArray = json_decode($item['data'], true);
                unset($item['data']);
                if (!$dataArray) {
                    return $item;
                }
                if (isset($dataArray['updated_at'])) {
                    $timeString = $dataArray['updated_at'] - strtotime($item['created_at']);
                    $item['review_in_duration'] = $this->timeFarmat($timeString);
                } else {
                    $item['review_in_duration'] = '0:00:00';
                }

                $duration = 0;
                if (isset($dataArray['duration'])) {
                    foreach ($dataArray['duration'] as $value) {
                        if (isset($value['review_audio_pause']) && isset($value['review_audio_begin'])) {
                            $duration += $value['review_audio_pause'] - $value['review_audio_begin'];
                        }
                        if (isset($value['review_video_pause']) && isset($value['review_video_begin'])) {
                            $duration += $value['review_video_pause'] - $value['review_video_begin'];
                        }
                    }
                    $item['play_duration'] = $this->timeFarmat($duration);
                } else {
                    $item['play_duration'] = '0:00:00';
                }
                $item['channel'] = isset($dataArray['channel']) ? $dataArray['channel'] : 'heywow';

                return $item;
            };
            return $this->handle($headers, $columnNames, $query, $whereId, '>', true, 1000, $jsonDecode);

        } else if ($type == 'recommend_course') {
            $columnNames = [
                'openid',
                '报名课程id',
                '报名课程所属阶段',
                '推荐课程id',
                '推荐课程所属阶段',
                '模板消息推送时间'
            ];
            $query = DB::table('recommend_course')
                ->where('sign_up_course_id', $id)
                ->select('id', 'openid', 'sign_up_course_id', 'sign_up_course_stage', 'recommend_course_id', 'recommend_course_stage', 'created_at')
                ->orderBy('id', 'asc');
            $whereId = 'id';
            return $this->handle($headers, $columnNames, $query, $whereId);
        }
    }

    private function str_putcsv($fileHandle, $input, $delimiter = ',', $enclosure = '"')
    {
        $item = [];
        foreach ($input as $v) {
            $item[] = mb_convert_encoding($v, 'GB18030');
        }
        $fp = $fileHandle;
        fputcsv($fp, $item, $delimiter, $enclosure);
    }

    /**
     * 进行数据处理, 并返回
     *
     * @param $headers array Response::stream的headers
     * @param $columnNames array 首行字段
     * @param $query Builder 查询bulder,
     * @param $whereId
     * @param string $whereOperator
     * @param bool $unsetId 是否去掉id, 默认去掉
     * @param int $perPage
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    private function handle($headers, $columnNames, $query, $whereId, $whereOperator = '>', $unsetId = true, $perPage = 1000, $callback = null)
    {
        $callback = function () use ($headers, $columnNames, $query, $whereId, $whereOperator, $unsetId, $perPage, $callback) {
            $fileHandle = fopen('php://output', 'w');
            $this->str_putcsv($fileHandle, $columnNames);
            $maxId = 0;
            do {
                // 注意这里必需要进行clone
                $queryCloned = clone $query;
                $items = $queryCloned->where($whereId, $whereOperator, $maxId)->limit($perPage)->get();
                if ($items) {
                    $lastItem = last($items);
                    $maxId = $lastItem->id;
                    foreach ($items as $item) {
                        $item = (array)$item;
                        if (is_callable($callback)) {
                            $item = call_user_func($callback, $item);
                        }
                        if ($unsetId) {
                            unset($item['id']);
                        }
                        $this->str_putcsv($fileHandle, $item);
                    }
                }
            } while ($items);
            fclose($fileHandle);
        };
        return Response::stream($callback, 200, $headers);
    }

    /*
     * 时间格式化
     */
    public  function timeFarmat($time)
    {
        $second = $time % 60;
        $minute = floor($time / 60);
        $hour = floor($minute / 60);
        $minute = $minute % 60;
        return  $hour . ':' . $minute . ':' . $second;
    }
}
