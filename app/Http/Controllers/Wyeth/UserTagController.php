<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/13
 * Time: 上午10:53
 */

namespace App\Http\Controllers\Wyeth;

use App\Repositories\UserTagRepository;
use Illuminate\Http\Request;
use App\Repositories\CourseRepository;
use App\Repositories\TagRepository;
use App\Repositories\AppConfigRepository;

class UserTagController extends WyethBaseController{
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

    public function chooseTag(Request $request){
        $tagIds = json_decode($request->input('tags'));
        $ret = $this->userTagRepository->chooseTag($tagIds);
        return $ret;
    }

    public function getChooseTag(){
        $data = $this->userTagRepository->getChooseTag();
        return $data;
    }

    public function getUserTag(){
        $data = $this->userTagRepository->getUserTag();
        return $data;
    }

    public function increaseTag(Request $request){
        $tagId = json_decode($request->input('tid'));
        $ret = $this->userTagRepository->increaseTag($tagId);
        return $ret;
    }

    public function decreaseTag(Request $request){
        $tagId = json_decode($request->input('tid'));
        $ret = $this->userTagRepository->decreaseTag($tagId);
        return $ret;
    }

    public function getConcernTag(){
        $data = $this->userTagRepository->getConcernTag();
        return $data;
    }
}