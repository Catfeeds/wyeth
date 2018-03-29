<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\CourseTag;
use App\Models\Tag;
use App\Models\UserTag;
use Illuminate\Console\Command;
use Log;

class UserDisplayTag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:display';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //预处理
        $courses = Course::where('display_status', 1)->get();
        $course_array = [];
        foreach ($courses as $course){
            $course_array[] = $course->id;
        }
        $display_tag_array = [];
        $content_tags = Tag::where('type', 0)->get();
        foreach ($content_tags as $content_tag) {
            if(!array_key_exists($content_tag->id, $display_tag_array)){
                $display_tag_array[$content_tag->id] = [];
                $course_tags = CourseTag::where('type', 0)->whereIn('cid', $course_array)->where('tid', $content_tag->id)->get();
                foreach ($course_tags as $course_tag){
                    $display_course_tags = CourseTag::where('type', 3)->where('cid', $course_tag->cid)->get();
                    foreach ($display_course_tags as $display_course_tag){
                        $display_tag_array[$content_tag->id][] = $display_course_tag->tid;
                    }
                }
            }
        }

        //先查找user_tag表中某用户关注了哪些内容tag
        $page_size = 500;
        $num = intval(UserTag::count() / $page_size) + 1;
        $time = microtime(true);
        for($i = 0; $i < $num; $i++){
            echo('cycle:' . $i . '/' . $num . "\n");
            Log::info('cycle:' . $i . '/' . $num . "\n");

            if($i != 0){
                $inteval = microtime(true) - $time;
                echo($inteval . "\n");
                Log::info($inteval . "\n");
                $time = microtime(true);
            }
            $user_tags = UserTag::where('type', 0)->take($page_size)->offset($i * $page_size)->orderBy('id', 'asc')->get();
            foreach ($user_tags as $user_tag){
                //查找该内容tag下有哪些课程
                foreach ($display_tag_array[$user_tag->tid] as $tid){
                    $display_user_tag = UserTag::where('uid', $user_tag->uid)->where('tid', $tid)->first();
                    if(!$display_user_tag){
                        $display_user_tag = new UserTag();
                        $display_user_tag->uid = $user_tag->uid;
                        $display_user_tag->tid = $tid;
                        $display_user_tag->type = 3;
                        $display_user_tag->save();
                    }
                }
//                $course_tags = CourseTag::where('tid', $user_tag->tid)->get();
//                foreach ($course_tags as $course_tag){
//                    //判断课程是否有效
//                    $course = Course::where('id', $course_tag->cid)->where('display_status', 1)->first();
//                    if(!$course){
//                        continue;
//                    }
//                    //查找这些课程对应的显示tag
//                    $display_course_tags = CourseTag::where('cid', $course_tag->cid)->where('type', 3)->get();
//                    if(count($display_course_tags) > 0){    //说明该课程有对应的显示tag
//                        //查看该用户是否已经关注过该显示tag,如果没有则关注上
//                        foreach ($display_course_tags as $display_course_tag){
//                            $display_user_tag = UserTag::where('uid', $user_tag->uid)->where('tid', $display_course_tag->tid)->first();
//                            if(!$display_user_tag){
//                                $display_user_tag = new UserTag();
//                                $display_user_tag->uid = $user_tag->uid;
//                                $display_user_tag->tid = $display_course_tag->tid;
//                                $display_user_tag->type = 3;
//                                $display_user_tag->save();
//                            }
//                        }
//                    }
//                }
            }
        }
    }
}
