<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/8/2
 * Time: 上午10:16
 */


namespace App\CIService;
use App\Models\CourseTag;
use App\Models\Course;
use App\Models\Tag;

use GuzzleHttp\Exception\RequestException;

use Illuminate\Support\Facades\DB;
class CIDataRecommend extends BaseCIService{

    protected $tagArray = [];

    public function updateTag(){
        $url = '/cidata/main.php/api/rec/tag/update.json';
        $tagArray = Tag::getNewTag();
        $addTags = [];
        $tags = Tag::where('type', 1)->get()->toArray();
        foreach ($tags as $tag){
            $addTags[] = $tag['id'];
        }
        $tagArray = array_merge($tagArray, $addTags);
        $teacherTags = $this->getActiveTeacherTag();
        $tagArray = array_merge($tagArray, $teacherTags);
        $this->tagArray = $tagArray;
        $tags = json_encode($tagArray);
        $params = [
            'app_id' => $this->appkey,
            'tags' => $tags
        ];
        $response = $this->post($url, $params, false);
        return $response;
    }

    public function getActiveTeacherTag(){
        $limitNUm = 5;
        //取出所有讲师tag
        $teacherTags = Tag::where('type', 2)->get()->toArray();
        $tagArray = [];
        //获取这些讲师的课程
        foreach ($teacherTags as $teacherTag){
            $originCourseTags = CourseTag::where('tid', $teacherTag['id'])->get()->toArray();
            $courses = [];
            //剔除display_status = 0的无效课程
            foreach ($originCourseTags as $originCourseTag){
                $course = Course::where('id', $originCourseTag['cid'])->first();
                if(($course != NULL) && ($course->display_status == 1)){
                    $courses[] = $course;
                }
            }
            if(count($courses) >= $limitNUm){
                $tagArray[] = $teacherTag['id'];
            }
        }

        //返回课程数大于等于9的讲师tag的id
        return $tagArray;
    }

    public function uploadItems(){
        var_dump($this->tagArray);
        $courses = Course::get()->toArray();
        $i = 0;
        foreach ($courses as $course) {
            $cid = $course['id'];
            $courseTags = CourseTag::where('cid', $cid)->whereIn('tid', $this->tagArray)->get()->toArray();
            if(count($courseTags) > 0){
                $k_v = [];
                $url = '/cidata/main.php/api/rec/item/update.json';
                $tid = 0;
                $weight = 0;
                foreach ($courseTags as $courseTag){
                    $tid = $courseTag['tid'];
                    $weight = $courseTag['weight'];
                    $k_v[(string)$courseTag['tid']] = $courseTag['weight'];
                }
                $props = json_encode($k_v);
                $params = [
                    'app_id' => $this->appkey,
                    'item_id' => $cid,
                    'props' => $props
                ];
                $response = $this->post($url, $params, false);
                $i++;
            }

        }
    }

    //上传包括孕龄以及讲师在内的tag
    public function uploadTeacherItems(){
        $courses = Course::get()->toArray();
        $i = 0;
        foreach ($courses as $course) {
            $cid = $course['id'];
            $courseTags = CourseTag::whereIn('type', [1,2])->where('cid', $cid)->get()->toArray();
            if(count($courseTags) > 0){
                $k_v = [];
                $url = '/cidata/main.php/api/rec/item/update.json';
                $weight = 0;
                foreach ($courseTags as $courseTag){
                    $weight = $courseTag['weight'];
                    $k_v[(string)$courseTag['tid']] = $courseTag['weight'];
                }
                $props = json_encode($k_v);
                $params = [
                    'app_id' => $this->appkey,
                    'item_id' => $cid,
                    'props' => $props
                ];
                $response = $this->post($url, $params, false);
                $i++;
            }

        }
    }

    public function uploadOneItem($cid, $tag_weight){
        if($tag_weight == NULL || !is_array($tag_weight)){
            return -1;
        }
        $url = '/cidata/main.php/api/rec/item/update.json';
        $tagArray = Tag::getNewTag();
        $tids = array_keys($tag_weight);
        $k_v = [];
        foreach ($tids as $tid){
            if(in_array($tid, $tagArray, false)){
                $k_v[(string)$tid] = $tag_weight[$tid];
            }
        }
        $props = json_encode($k_v);
        $params = [
            'app_id' => $this->appkey,
            'item_id' => $cid,
            'props' => $props
        ];
        $response = $this->post($url, $params, false);
        return $response['ret'];
    }

    public function setTags($uid, $tags){
        $url = '/cidata/main.php/api/rec/user/setTags.json';
        $params = [
            'app_id' => $this->appkey,
            'user_id' => $uid,
            'tags' => $tags
        ];
        $response = $this->post($url, $params, false);
        return $response['ret'];
    }

    public function recomend($uid, $max_items = 50){
        $url = '/recommendation/get_recommend';
        $params = [
            'app_id' => $this->appkey,
            'user_id' => $uid,
            'count' => $max_items
        ];
        try{
            $response = $this->short_post($url, $params, false);
        }catch (RequestException $e){
            $response = [];
        }
        return $response;
    }

}