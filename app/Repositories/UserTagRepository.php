<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/13
 * Time: 上午10:36
 */
namespace App\Repositories;


use App\Helpers\WyethError;
use App\Models\CiAppConfig;
use App\Models\CourseTag;
use App\Models\Tag;
use App\Models\UserTag;
use GuzzleHttp\Exception\RequestException;

use Auth;
use App\CIService\CIDataRecommend;
class UserTagRepository extends BaseRepository{
    public function getUserTag(){
        $uid = Auth::id();
        $userTag = UserTag::where('uid', $uid)->whereIn('type', [0,1])->get()->toArray();
        $data = [];
        foreach ($userTag as $row){
            $tid = $row['tid'];
            $tag = Tag::where('id', $tid)->get()->toArray();
            if (count($tag) > 0) {
                $name = $tag[0]['name'];
                $data[] = $name;
            }
        }
        return $this->returnData($data);
    }

    public function chooseTag($tagIds){
        $ciDataRecommend = new CIDataRecommend();
        $uid = Auth::id();
        $tags = json_encode($tagIds);
        $tagArray = $this->getTagArray();
        UserTag::where('uid', $uid)->whereIn('tid', $tagArray)->delete();
        if(is_array($tagIds)){
            $ageTags = $this->getAgeTagArray();
            if(!$this->judgeInArray($tagIds)){
                $tagIds = array_merge($tagIds, $ageTags);
            }
            foreach ($tagIds as $tagId){
                $this->increaseTag($tagId);
            }
        }
        try{
            $ret = $ciDataRecommend->setTags($uid, $tags);
        }catch (RequestException $e){
            $ret = $ciDataRecommend->setTags($uid, $tags);
        }

        if($ret == 0){
            return $this->returnData();
        }else{
            return $this->returnError('上传标签失败');
        }
    }

    public function getChooseTag(){
        $tagIds = CiAppConfig::ci_focus_tags(true);
        return $this->returnData($tagIds);
    }

    public function increaseTag($tid){
        $uid = Auth::id();
        $tag = Tag::where('id', $tid)->limit(1)->get()->toArray();
        if(count($tag) != 0){
            $userTag = UserTag::where('uid', $uid)->where('tid', $tid)->get()->toArray();
            if(count($userTag) == 0){
                $userTag = new UserTag();
                $userTag->uid = $uid;
                $userTag->tid = $tid;
                $userTag->type = $tag[0]['type'];
                $userTag->save();
            }
            return $this->returnData([]);
        }else {
            return (new WyethError())->TAG_NOT_EXIST;
        }
    }

    public function decreaseTag($tid){
        $uid = Auth::id();
        $tag = Tag::where('id', $tid)->get()->toArray();
        if(count($tag) != 0){
            UserTag::where('uid', $uid)->where('tid', $tid)->delete();
            return $this->returnData([]);
        }else{
            return (new WyethError())->TAG_NOT_EXIST;
        }
    }

    public function getConcernTag(){
        $tagArray = $this->getTagArray();
        $concerned = [];
        $notConcerned = [];
        foreach ($tagArray as $tagId){
            $userTag = UserTag::where('uid', Auth::id())->where('tid', $tagId)->first();
            $tag = Tag::where('id', $tagId)->first();
            if($userTag != NULL){
                $concerned[] = [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'img' => $tag->img,
                    'type' => $tag->type
                ];
            }else{
                $notConcerned[] = [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'img' => $tag->img,
                    'type' => $tag->type
                ];
            }
        }
        $result = [
            'concerned' => $concerned,
            'not_concerned' => $notConcerned
        ];
        return $this->returnData($result);
    }

    public function getTagArray(){
        $tagArray = Tag::getNewTag();
        $addTags = [];
        $tags = Tag::where('type', 1)->get()->toArray();
        foreach ($tags as $tag){
            $addTags[] = $tag['id'];
        }
        $tagArray = array_merge($tagArray, $addTags);
        $teacherTags = (new CIDataRecommend())->getActiveTeacherTag();
        $tagArray = array_merge($tagArray, $teacherTags);
        return $tagArray;
    }

    public function getAgeTagArray(){
        $tags = Tag::where('type', 1)->get()->toArray();
        $data = [];
        foreach ($tags as $tag){
            $data[] = $tag['id'];
        }
        return $data;
    }

    public function judgeInArray($array){
        $tags = $this->getAgeTagArray();
        foreach ($tags as $tag){
            if(in_array($tag, $array)){
                return true;
            }
        }
        return false;
    }
}