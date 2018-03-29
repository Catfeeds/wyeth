<?php

namespace App\Console\Commands;

use App\Jobs\CreateTplmsgFromCourse;
use App\Models\Course;
use App\Models\CoursePush;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class CoursePushCommand extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '课程定时推送';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $course_push = CoursePush::where('status', CoursePush::COURSE_PUSH_WAIT)
            ->where('push_time', '<', date('Y-m-d H:i:s'))
            ->get()
            ->toArray();
        foreach ($course_push as $item){
            $cid = $item['cid'];
            $sign_start = $item['sign_start'];
            $sign_end = $item['sign_end'];

            $course = Course::find($cid);
            if ($course){
                $template_id = $course->notify_template_id;
                $params = [
                    'cid' => $course->id,
                    'user_shop' => 1,
                    'inclass_status' => 1,
                    'template_id' => $course->notify_template_id,
                ];
                $tpl_remark = "[".date('Y-m-d H:i:s')."] 定时推送模板消息$template_id 推送报名时间段";

                //有指定报名区间的话
                if ($sign_start && $sign_start !== '0000-00-00 00:00:00'){
                    $params['sign_start'] = $sign_start;
                    $tpl_remark .= "$sign_start ";
                }
                if ($sign_end && $sign_end !== '0000-00-00 00:00:00'){
                    $params['sign_end'] = $sign_end;
                    $tpl_remark .= "到 $sign_end";
                }
                $course->remark .= $tpl_remark."\n";
                $course->save();

                $job = new CreateTplmsgFromCourse($params);
                $this->dispatch($job);

                //更新已推送
                $update_course_push = CoursePush::find($item['id']);
                $update_course_push->status = CoursePush::COURSE_PUSH_SEND;
                $update_course_push->save();
                \Log::info('course定时推送', $params);
                $this->info($item['id'] . '---' . '推送课程' . $cid);
            }
        }
    }
}
