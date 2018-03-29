<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/9/25
 * Time: 下午1:53
 */

namespace App\Repositories;

use App\Models\TagQuestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Session;
use Log;
use Cache;

class TagQuestionRepository extends BaseRepository
{
    public function getTagQuestion($tid){
        $scan_num = TagQuestion::where('id', $tid)->first()->scan_num + 1;
        TagQuestion::where('id', $tid)->update([
            'scan_num' => $scan_num,
        ]);
        $tag_question = TagQuestion::where('id', $tid)->first();
        $user = Auth::user();
        $courses = (new CourseRepository())->endCoursesRecommended($user->id, $user->type, 'review', 10, 0, true);
        if(count($courses) <= 3){
            $recom_courses = $courses;
        }else{
            $num = range(0, count($courses) - 1);
            shuffle($num);
            $recom_courses = [];
            foreach ($num as $v){
                $recom_courses[] = $courses[$v];
                if(count($recom_courses) >= 3){
                    break;
                }
            }
        }
        $data = [
            'tag_question' => $tag_question,
            'recomClass' => $recom_courses
        ];
        return $this->returnData($data);
    }
}