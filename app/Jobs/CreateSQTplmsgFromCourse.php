<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Jobs\SendTemplateMessage;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Course;
use App\Models\UserCourse;
use DB;

/**
 * 从课程创建手q的模板消息发送
 * Class CreateSQTplmsgFromCourse
 * @package App\Jobs
 */
class CreateSQTplmsgFromCourse extends Job implements SelfHandling, ShouldQueue
{

    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    private $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->params['cid']) {
            return '';
        }
        $course = Course::find($this->params['cid']);
        if ($course) {
            $cid = $course->id;

            //generating
            $params = [
                //'accessToken' => $cryptnStr,
                //'templateid' => '52c0727fbd1873fa', //temp
                'first' => $course->notify_content, //开头内容
                'keynote1' => $course->notify_title, //标题
                'keynote2' => $course->notify_odate, //开课时间
                'keynote3' => $course->notify_address, //开课地点
                'url' => $course->notify_url , //jump link
                //'source' => '微课堂', //来源
            ];

            // 查询报名用户
            $inclass_status = $this->params['inclass_status'];
            $user_shop = $this->params['user_shop'];

            $query = DB::table('user_course')
                ->leftJoin('user', 'user.id', '=', 'user_course.uid')
                ->where('user_course.cid', $cid)
                ->where('user.type', User::OPENID_TYPE_SQ)
                ->select('user.openid');
            if ($inclass_status == 1) {
                // 全部报名用户
            } else if ($inclass_status == 2) {
                // 上课用户
                $query->where('user_course.listen_time', '>', 0);
            } else if ($inclass_status == 3) {
                // 不上课用户
                $query->where('user_course.listen_time', 0);
            }
            // 每次执行的条数限制
            $perPage = 3000;
            $query->chunk($perPage, function($items) use ($params, $cid) {
                \Log::debug("CreateSQTplmsgFromCourse Callback: " .json_encode($params, JSON_UNESCAPED_UNICODE));
                foreach ($items as $item) {
                    $openid = $item->openid;
                    // \Log::debug("CreateTplmsgFromCourse: " . $openid);
                    if ($openid) {
                        $this->dispatch(new SendSQTemplateMessage($openid, $params, $cid));
                    }
                }
            });
        }
    }
}
