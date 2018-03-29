<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/12
 * Time: 下午3:23
 */

namespace App\Http\Controllers\Wyeth;

use App\Repositories\UserTagRepository;
use Illuminate\Http\Request;
use App\Repositories\CourseRepository;
use App\Repositories\TagRepository;
use App\Repositories\AppConfigRepository;
use App\Models\CourseTag;

class TagController extends WyethBaseController{

    protected $courseRepository;
    protected $tagRepository;
    protected $appConfigRepository;
    protected $userTagRepository;

    public function __construct()
    {
        parent::__construct();
        $this->courseRepository = new CourseRepository();
        $this->tagRepository = new TagRepository();
        $this->appConfigRepository = new AppConfigRepository();
        $this->userTagRepository = new UserTagRepository();
    }

    public function getHomeTag(Request $request){
        $data = $this->tagRepository->getHomeTags();
        return $data;
    }

    public function getAllHotTag(Request $request){
        $data = $this->tagRepository->getAllHotTag();
        return $data;
    }

    //获取全部页面的tag
    public function getAllTag(Request $request){
        $preg_tag = $this->tagRepository->getPregTags();
        $hot_tag = $this->tagRepository->getAllHotTag();
        return $this->returnData([
            'preg_tag' => $preg_tag['data'],
            'hot_tag' => $hot_tag['data']
        ]);
    }

}