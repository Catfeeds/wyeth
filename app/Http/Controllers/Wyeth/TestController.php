<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/7
 * Time: 下午2:19
 */

namespace App\Http\Controllers\Wyeth;


use App\Repositories\CourseRepository;
use App\Repositories\TagRepository;
use Illuminate\Support\Facades\Auth;

class TestController extends WyethBaseController
{
    protected $courseRepository;
    protected $tagRepository;

    public function __construct()
    {
        parent::__construct();
        $this->courseRepository = new CourseRepository();
        $this->tagRepository = new TagRepository();
    }

    public function test(){

        return response()->json($this->error->UNKNOWN_ERROR);

        $courseRecommend = $this->courseRepository->getCourseRecommend();
        $hotTag = $this->tagRepository->getHotTags();

        return response()->json([
            'ret' => 1,
            'hotTag' => $hotTag,
            'courseRecomment' => $courseRecommend
        ]);
    }
}