<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/7
 * Time: 下午3:15
 */

namespace App\Repositories;

use App\Repositories\UserRepository;
use App\Models\AppConfig;
use App\Models\CiAppConfig;
use App\Models\CourseTag;

use App\Models\Tag;
use Auth;
class TagRepository extends BaseRepository
{
    protected $homeTagNum = 6;

    public function getHotTags(){
        $hotTags = CourseTag::select('course_tags.cid', 'course_tags.tid', 'tags.name')
            ->leftjoin('tags', 'tags.id', '=', 'course_tags.tid')
            ->limit(8)
            ->get()
            ->toArray();
        return $hotTags;
    }

    public function getHomeTags(){
        $tags = CiAppConfig::ci_index_tags((new UserRepository())->getUserType(Auth::id()),true);
        return $this->returnData($tags);
    }

    public function getAllHotTag(){
        $tags = CiAppConfig::ci_all_hot_tags(true);
        return $this->returnData($tags);
    }

    //获取孕期标签
    public function getPregTags(){
        $tags = Tag::where('type', Tag::TAG_PREGNANT)
            ->select('id', 'name', 'img', 'type', 'interest_img')
            ->get()
            ->toArray();
        return $this->returnData($tags);
    }

}