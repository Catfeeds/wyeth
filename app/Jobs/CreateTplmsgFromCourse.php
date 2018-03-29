<?php

namespace App\Jobs;

use DB;
use App\Jobs\Job;
use App\Jobs\CreateTplmsgFromOpenids;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Course;
use App\Models\UserCourse;

class CreateTplmsgFromCourse extends Job implements SelfHandling, ShouldQueue
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
        if (!$course) {
            return '';
        }

        $cid = $course->id;
        $params = [
            'title' => $course->notify_title,
            'content' => $course->notify_content,
            'odate' => $course->notify_odate ?: $course->start_date . ' ' . date("H:i", strtotime($course->start_time)),
            'address' => $course->notify_address,
            'remark' => "\n" . $course->notify_remark,
            'url' => $course->notify_url,
        ];

        // 查询报名用户
        $inclass_status = $this->params['inclass_status'];
        $user_shop = $this->params['user_shop'];

        //暂时去掉非慧摇报名的用户
        $query = DB::table('user_course')
            ->leftJoin('user', 'user.id', '=', 'user_course.uid')
            ->where('user_course.cid', $cid)
            ->where('user_course.channel', 'hongbao2016')
            ->where('user.type', User::OPENID_TYPE_WX)
            ->where('user.subscribe_status', User::WX_SUBSCRIBE_STATUS_YES)
            ->select('user_course.id', 'user.openid')
            ->orderBy('user_course.id', 'asc');
        if ($inclass_status == 1) {
            // 全部报名用户
        } else if ($inclass_status == 2) {
            // 上课用户
            $query->where('user_course.listen_time', '>', 0);
        } else if ($inclass_status == 3) {
            // 不上课用户
            $query->where('user_course.listen_time', 0);
        }
        //报名时间段设置
        if (isset($this->params['sign_start']) && $this->params['sign_start']){
            $query->where('user_course.created_at', '>', $this->params['sign_start']);
        }
        if (isset($this->params['sign_end']) && $this->params['sign_start']){
            $query->where('user_course.created_at', '<', $this->params['sign_end']);
        }

        // 每次执行的条数限制 注意msn消息体大小要小于64k
        $perPage = 1000;
        $maxId = 0;
        $i = 0;
        do {
            $queryCloned = clone $query;
            $items = $queryCloned->where('user_course.id', '>', $maxId)->limit($perPage)->get();
            if ($items) {
                $lastItem = last($items);
                $maxId = $lastItem->id;
                \Log::debug("CreateTplmsgFromCourse: S $i\t$maxId\t" .json_encode($params, JSON_UNESCAPED_UNICODE));
                $opendis = array_pluck($items, 'openid');
                $job = (new CreateTplmsgFromOpenids($params, $opendis, $user_shop, $this->params['template_id']));
                $this->dispatch($job);
                \Log::debug("CreateTplmsgFromCourse: E $i\t$maxId\t" .json_encode($params, JSON_UNESCAPED_UNICODE));
                $i = $i + count($items);
            }
        } while ($items);
    }
}
