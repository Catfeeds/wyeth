<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\AppConfig;
use App\Models\Course;
use App\Models\CoursePush;
use App\Models\RecommendCourse;
use App\Models\TplProject;
use App\Services\WxWyeth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis as Redis;

//自动推送的每天的回顾推荐
class CreateTplmsgFromAutoPushTjhg extends Job implements SelfHandling, ShouldQueue
{

    use InteractsWithQueue, SerializesModels;

    protected $uid;
    protected $openid;
    protected $cid;
    protected $need_check;
    //为模板消息自动推送专门设个type,存储在tplmsg里区分其他的
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uid, $openid, $cid, $need_check = true, $type = 14)
    {
        $this->uid = $uid;
        $this->openid = $openid;
        $this->cid = $cid;
        $this->need_check = $need_check;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $course = Course::find($this->cid);
        if (!$course){
            return;
        }

        //根据type获取channel
        $type_channel = CoursePush::getTypeChannel();
        if (isset($type_channel[$this->type])){
            $channel = $type_channel[$this->type];
        }else{
            return;
        }

        $url = replaceUrlParams($course->notify_url, '_hw_c', $channel);

        $params = [
            'openid' => $this->openid,
            'title' => $course->notify_title,
            'content' => $course->notify_content,
            'odate' => $course->notify_odate ?: $course->start_date . ' ' . date("H:i", strtotime($course->start_time)),
            'address' => $course->notify_address,
            'remark' => "\n" . $course->notify_remark,
            'url' => $url,
            'type' => $this->type
        ];


        $wxWyeth = new WxWyeth();
        $wxWyeth->pushpushCustomMessage($params, 4, $this->need_check);


        if ($this->uid){
            $recommendCourse = new RecommendCourse();
            $recommendCourse->uid = $this->uid;
            $recommendCourse->openid = $this->openid;
            $recommendCourse->sign_up_course_id = 0;
            $recommendCourse->recommend_course_id = $this->cid;
            $recommendCourse->save();
        }
    }
}
