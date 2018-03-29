<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/28
 * Time: 上午11:11
 */


namespace App\Repositories;

use App\Helpers\CacheKey;
use App\Models\CiAppConfig;
use App\Models\Course;
use App\Models\CourseReview;
use App\Models\CourseTag;
use App\Models\Tag;
use App\Models\UserTag;
use App\Models\TagQuestion;
use App\Models\Teacher;
use App\Services\CourseService;
use App\Services\Crm;
use App\Services\SphinxSearch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\AppConfig;

use Session;
use Log;
use Cache;

class SearchRepository extends BaseRepository
{
    const JIN_ZHUANG = 4;

    const QI_FU = 10;

    //金装课brand数组
    protected $JIN_ZHUANG_ARRAY = [4, 5, 8];

    //启赋课brand数组
    protected $QI_FU_ARRAY = [10, 11, 12];

    protected $page_size = 10;
    protected $search_size = 50;

    //专门返回课程数据的接口
    public function getSearchResult($keyword, $page){
        $sphinxSearch = new SphinxSearch();
        if($page == NULL || $page < 1){
            $page = 1;
        }
        $data = [];
        //先去缓存中取
        $courseIdArray = $this->getArrayFromCache($keyword);
        //如果取到了,直接获取course数据并返回
        if($courseIdArray){
            $data = $this->searchDataFromIdArray($courseIdArray, $page);
            return $this->returnData($data);
        }else{
            $courseIdArray = [];
        }

        //搜索课程开始
        $searchCourseData = $sphinxSearch->getCourseSearch($keyword, $this->search_size);
        $courseMatches = [];
        if(is_array($searchCourseData) && $searchCourseData != NULL && $searchCourseData['matches'] != NULL){
            //从搜索结果的matches中得到数据
            $courseMatches = $searchCourseData['matches'];
        }
        //如果有数据,那么把id添加到courseIdArray中,并计算需要搜索的tag_id的数目
        if(is_array($courseMatches)){
            foreach ($courseMatches as $courseMatch) {
                if(!in_array($courseMatch['id'], $courseIdArray)){
                    $courseIdArray[] = $courseMatch['id'];
                }
            }
        }
        //搜索课程结束

//        //搜索tag表,返回tag_id
//        $searchTagData = $sphinxSearch->getTagSearch($keyword, $this->search_size);
//        if(is_array($searchTagData) && $searchTagData != NULL && $searchTagData['matches'] != NULL){
//            //同样从matches中获取数据
//            $tagMatches = $searchTagData['matches'];
//            //从tagMatches中获取course_id并添加到courseIdArray中
//            $courseIdArray = $this->searchCourseFromTag($tagMatches, $courseIdArray);
//        }
//        //如果有数据,那么把id添加到courseIdArray中,并计算需要搜索的tag_id的数目
        $course_num = $this->search_size - count($courseIdArray);

        //如果没取到,那么开始搜索course表
        //注意,此处course_num必须大于0,否则会报错
        if($course_num > 0){
//            $searchCourseData = $sphinxSearch->getCourseSearch($keyword, $course_num);
//            $courseMatches = [];
//            if(is_array($searchCourseData) && $searchCourseData != NULL && $searchCourseData['matches'] != NULL){
//                //从搜索结果的matches中得到数据
//                $courseMatches = $searchCourseData['matches'];
//            }
//            //如果有数据,那么把id添加到courseIdArray中,并计算需要搜索的tag_id的数目
//            if(is_array($courseMatches)){
//                foreach ($courseMatches as $courseMatch) {
//                    if(!in_array($courseMatch['id'], $courseIdArray)){
//                        $courseIdArray[] = $courseMatch['id'];
//                    }
//                }
//            }

            //搜索tag表,返回tag_id
            $searchTagData = $sphinxSearch->getTagSearch($keyword, $this->search_size);
            if(is_array($searchTagData) && $searchTagData != NULL && $searchTagData['matches'] != NULL){
                //同样从matches中获取数据
                $tagMatches = $searchTagData['matches'];
                //从tagMatches中获取course_id并添加到courseIdArray中
//                $courseIdArray = $this->searchCourseFromTag($tagMatches, $courseIdArray);
                $courseIdArray = array_merge($this->searchCourseFromTag($tagMatches, $courseIdArray), $courseIdArray);
            }
            //如果有数据,那么把id添加到courseIdArray中,并计算需要搜索的tag_id的数目
//            $course_num = $this->search_size - count($courseIdArray);
        }

        if(count($courseIdArray) > 0){
            //先对courseIdArray作处理
            $newCourseIdArray = [];
            foreach ($courseIdArray as $k => $item){
                $course = Course::where('id', $item)->whereNotIn('brand', $this->getNoBrandArray())->first();
                if($course && CourseService::filterCourse($course)){
                    $newCourseIdArray[] = $item;
                }
            }
            $data = $this->searchDataFromIdArray($newCourseIdArray, $page);
            $this->saveArrayToCache($keyword, $newCourseIdArray);
            return $this->returnData($data);
        }else{
            return $this->returnData($data);
        }
    }

    //返回综合数据的搜索方法
    public function getNewSearchResult($keyword, $page){
        $courseNum = 1;
        $questionNum = 1;
        $sphinxSearch = new SphinxSearch();
        $search_result = $this->getSearchResult($keyword, $page);
        $course = [];
        if(array_key_exists('data', $search_result) && is_array($search_result['data']) && count($search_result['data']) > 0){
            for($i = 0; $i < $courseNum; $i++){
                if($i >= count($search_result)){
                    break;
                }
                $search_result['data'][$i]['type'] = 1;
                $course[] = $search_result['data'][$i];
            }
        }
        $question = $sphinxSearch->getQuestionSearch($keyword, $this->search_size);
        $question_result = [];
        if(is_array($question) && array_key_exists('matches', $question)){
            if(is_array($question['matches']) && count($question['matches']) > 0){
                for($i = 0; $i < $questionNum; $i++){
                    if($i >= count($question['matches'])){
                        break;
                    }
                    $tagQuestion = TagQuestion::where('id', $question['matches'][$i]['id'])->first();
                    $tq = [
                        'id' => $tagQuestion->id,
                        'keyword' => $tagQuestion->keyword,
                        'question' => $tagQuestion->question,
                        'answer' => $tagQuestion->answer,
                        'type' => 0
                    ];
                    $question_result[] = $tq;
                }
            }
        }
        $tag_result = $sphinxSearch->getTagSearch($keyword, 5);
        $teacher_info = [];
        if(is_array($tag_result) && array_key_exists('matches', $tag_result)){
            if(is_array($tag_result['matches']) && count($tag_result['matches']) > 0){
                $tid = $tag_result['matches'][0]['id'];
                $tag = Tag::where('id', $tid)->first();
                if($tag->type == 2){
                    $teacher = Teacher::where('tid', $tid)->first();
                    $count = UserTag::where('tid', $teacher->tid)->count();
                    $teacher_info = [
                        'id' => $teacher->id,
                        'name' => $teacher->name,
                        'avatar' => $teacher->avatar,
                        'position' => $teacher->position,
                        'hospital' => $teacher->hospital,
                        'desc' => $teacher->desc,
                        'tid' => $teacher->tid,
                        'count' => $count,
                        'type' => 2,
                    ];
                }
            }
        }
        $result = [
            'course' => $course,
            'question' => $question_result
        ];
        if(count($teacher_info) > 0){
            $result['teacher'] = $teacher_info;
        }
        return $this->returnData($result);
    }

    public function getQuestionSearch($keyword, $page){
        $sphinxSearch = new SphinxSearch();
        $question = $sphinxSearch->getQuestionSearch($keyword, $this->search_size);
        $qIdArray = [];
        if(is_array($question) && array_key_exists('matches', $question)){
            if(is_array($question['matches']) && count($question['matches']) > 0){
                foreach ($question['matches'] as $item){
                    $qIdArray[] = $item['id'];
                }
            }
        }
        $result = $this->searchDataFromQIdArray($qIdArray, $page);
        return $this->returnData($result);
    }

    //从tag_question_id构成的数组中获取数据
    public function searchDataFromQIdArray($qIdArray, $page){
        if($page == NULL || $page < 1){
            $page = 1;
        }
        $data = [];
        $last_index = ($page - 1) * $this->page_size;
        if(count($qIdArray) <= ($page - 1) * $this->page_size){
            return $data;
        }elseif (count($qIdArray) <= $page * $this->page_size){
            for($i = 0; $i < (count($qIdArray) - $last_index); $i++){
                $result = TagQuestion::where('id', $qIdArray[$last_index + $i])->first();
                if($result){
                    $data[] = $result;
                }
            }
            return $data;
        }else{
            for($i = 0; $i < $this->page_size; $i++){
                $result = TagQuestion::where('id', $qIdArray[$last_index + $i])->first();
                if($result){
                    $data[] = $result;
                }
            }
            return $data;
        }
    }

    //从course_id构成的数组中获取数据
    public function searchDataFromIdArray($courseIdArray, $page){
        $uid = Auth::id();
        if($page == NULL || $page < 1){
            $page = 1;
        }
        $data = [];
        $last_index = ($page - 1) * $this->page_size;
        if(count($courseIdArray) <= ($page - 1) * $this->page_size){
            return $data;
        }elseif (count($courseIdArray) <= $page * $this->page_size){
            for($i = 0; $i < (count($courseIdArray) - $last_index); $i++){
                $result = $this->getCourseInfo($courseIdArray[$last_index + $i]);
                if(count($result) > 0){
                    $data[] = $result;
                }
            }
            return $data;
        }else{
            for($i = 0; $i < $this->page_size; $i++){
                $result = $this->getCourseInfo($courseIdArray[$last_index + $i]);
                if(count($result) > 0){
                    $data[] = $result;
                }
            }
            return $data;
        }
    }

    //获取tagMatches中的tag_id,并且去course_tags表中获取该tag_id对应的course_id,注意去重
    public function searchCourseFromTag($tagMatches, $course_array){
        $courseIdArray = $course_array;
        foreach ($tagMatches as $tagMatch){
            $courseTags = CourseTag::where('tid', $tagMatch['id'])->whereNotIn('cid', $courseIdArray)->get()->toArray();
            if(count($courseTags) > 0){
                foreach ($courseTags as $courseTag){
                    $courseIdArray[] = $courseTag['cid'];
                }
            }
        }
        return $courseIdArray;
    }

    public function getArrayFromCache($keyword){
        $brand = (new Crm())->getMemberBrand();
        $data = Cache::get(CacheKey::SEARCH_RESULT . $keyword . $brand);
        return $data;
    }

    public function saveArrayToCache($keyword, $courseIdArray){
        $brand = (new Crm())->getMemberBrand();
        Cache::put(CacheKey::SEARCH_RESULT . $keyword . $brand, $courseIdArray, 5);
    }

    public function getCourseInfo($cid){
        $uid = Auth::id();
        $result = Course::where('id', $cid)->first();
        if(!$result || !CourseService::filterCourse($result)){
            return [];
        }
        return CourseService::getCourseInfoById($uid, $cid);
    }

    public function getSearchTag(){
        $data = CiAppConfig::ci_hot_tags(true);
        return $this->returnData($data);
    }

    public function getNoBrandArray(){
        $brand = (new Crm())->getMemberBrand();
        $no_brand_array = [];
        if($brand == self::QI_FU){
            $no_brand_array = $this->JIN_ZHUANG_ARRAY;
        }elseif($brand == self::JIN_ZHUANG){
            $no_brand_array = $this->QI_FU_ARRAY;
        }
        return $no_brand_array;
    }
}