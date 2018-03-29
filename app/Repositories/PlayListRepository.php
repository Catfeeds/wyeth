<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/9/25
 * Time: 下午5:12
 */

namespace App\Repositories;

use App\CIService\CIDataRecommend;
use App\Helpers\CacheKey;
use App\Models\Course;
use App\Models\CourseReview;
use App\Models\UserTag;
use App\Services\CourseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Exception\RequestException;

use Illuminate\Foundation\Bus\DispatchesJobs;

use Session;
use Log;
use Cache;

class PlayListRepository extends BaseRepository{
    use DispatchesJobs;
    protected $course_num = 6;

    protected $recom_course_num = 40;

    protected $play_list_num = 30;

    public function getPlayList($uid, $ids = []){
        if(is_array($ids) && count($ids) > 0){
            return $this->refreshPlayList($uid, $ids);
        }else{
            return $this->getFirstPlayList($uid);
        }
    }

    public function refreshPlayList($uid, $ids){
        $data = Cache::get(CacheKey::PLAYLIST_DATA . $uid);
        if(!is_array($ids) || !$data || !is_array($data) || count($data) == 0){
            return $this->getPlayList($uid);
        }
        $result = [];
        foreach ($data as $course){
            if(!in_array($course['id'], $ids)){
                $result[] = $course;
            }
        }
        shuffle($result);
        return $this->returnData(array_slice($result, 0, 6));
    }

    public function getFirstPlayList($uid){
        $data = Cache::get(CacheKey::PLAYLIST_DATA . $uid);
        if($data && count($data) < $this->course_num){
            return $this->returnData($data);
        }elseif($data && count($data) >= $this->course_num){
            return $this->returnData(array_slice($data, 0, 6));
        }
        $ciDataREcommend = new CIDataRecommend();
        $response = $ciDataREcommend->recomend($uid);
        if(is_array($response) && array_key_exists('data', $response) && array_key_exists('items', $response['data'])){
            $items = $response['data']['items'];
            $course_array = [];
            if(is_array($items)){
                $courses = Course::whereIn('id', $items)->where('display_status', 1)->get();
                foreach ($courses as $course){
//                    $course = Course::where('id', $item)->where('display_status', 1)->first();
                    $course_review = CourseReview::where('cid', $course->id)->first();
                    if($course && CourseService::filterCourse($course) && $course_review && $course_review->review_type == 1){
                        $course = CourseService::getCourseInfoById($uid, $course->id);
                        $course['audio_duration'] = $course_review->audio_duration;
                        $course['src'] = $course_review->audio;
                        $course_array[] = $course;
                    }
                }
                $all_courses = Course::where('display_status', 1)->orderBy('id', 'desc')->limit(2 * $this->play_list_num)->get()->toArray();
                foreach ($all_courses as $all_course){
                    if(!in_array($all_course['id'], $items)){
                        $all_course_review = CourseReview::where('cid', $all_course['id'])->first();
                        if(CourseService::filterCourse($all_course) && $all_course_review && $all_course_review->review_type == 1){
                            $course = CourseService::getCourseInfoById($uid, $all_course['id']);
                            $course['audio_duration'] = $all_course_review->audio_duration;
                            $course['src'] = $all_course_review->audio;
                            $course_array[] = $course;
                        }
                        if(count($course_array) >= $this->play_list_num){
                            break;
                        }
                    }
                }
                Cache::put(CacheKey::PLAYLIST_DATA . $uid, $course_array, 60);
                if(count($course_array) <= $this->course_num){
                    return $this->returnData($course_array);
                }else{
                    return $this->returnData(array_slice($course_array, 0, 6));
                }
            }else{
                return $this->returnData([]);
            }
        }else{
            return $this->returnData([]);
        }
    }
}