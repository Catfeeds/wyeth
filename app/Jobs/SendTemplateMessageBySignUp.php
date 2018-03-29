<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Services\WxWyeth;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\RecommendCourse;
use Illuminate\Support\Facades\Redis as Redis;
use Log;

class SendTemplateMessageBySignUp extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $tpl_params;
    protected $need_check;
    protected $template_id;
    protected $sign_up_course;
    protected $recommend_course;
    protected $uid;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->tpl_params = $params['tpl_params'];
        $this->need_check = $params['need_check'];
        $this->template_id = $params['template_id'];
        $this->sign_up_course = $params['sign_up_course'];
        $this->recommend_course = $params['recommend_course'];
        $this->uid = $params['uid'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $wxWyeth = new WxWyeth();
        $openid = $this->tpl_params['openid'];

        // 课程回顾模板消息发送前先判断用户是否关注
        $subscribeStatus = $wxWyeth->getSubscribeStatus($openid);
        if (!$subscribeStatus) {
            return;
        }

        // 限制重复发送
        $getTokenKey = 'preventRepeatOpenidForShake:'. $openid;
        if (Redis::ttl($getTokenKey) > 0) {
            Log::info(__METHOD__, [
                'uid' => $this->uid,
                'sid' =>  $this->sign_up_course->id,
                'rid' => $this->recommend_course->id
            ]);
            return;
        }
        Redis::set($getTokenKey, 1);
        Redis::expire($getTokenKey, 15);

        $wxWyeth->pushpushCustomMessage($this->tpl_params, $this->template_id, $this->need_check);
        $recommendCourse = new RecommendCourse;
        $recommendCourse->uid = $this->uid;
        $recommendCourse->openid = $openid;
        $recommendCourse->sign_up_course_id = $this->sign_up_course->id;
        $recommendCourse->sign_up_course_stage = $this->sign_up_course->stage_from . ' - ' . $this->sign_up_course->stage_to;
        $recommendCourse->recommend_course_id = $this->recommend_course->id;
        $recommendCourse->recommend_course_stage = $this->recommend_course->stage_from . ' - ' . $this->recommend_course->stage_to;
        $recommendCourse->save();



    }
}
