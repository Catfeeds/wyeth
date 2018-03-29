<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/8/21
 * Time: 上午11:37
 */
namespace App\Http\Controllers\Wyeth;


use App\Helpers\CacheKey;
use App\Models\Advertise;
use App\Models\User;
use App\Repositories\CourseListenRepository;
use App\Repositories\TaskRepository;
use App\Repositories\CourseRepository;
use App\Repositories\SearchRepository;
use App\Repositories\TagRepository;
use App\Repositories\AppConfigRepository;
use App\Repositories\UserRepository;
use App\Services\MqService;
use App\Services\Pregnotice;
use Illuminate\Http\Request;
use Auth;
use Cache;

class PageController extends WyethBaseController{
    protected $courseRepository;
    protected $tagRepository;
    protected $appConfigRepository;
    protected $searchRepository;

    public function __construct()
    {
        parent::__construct();
        $this->courseRepository = new CourseRepository();
        $this->tagRepository = new TagRepository();
        $this->appConfigRepository = new AppConfigRepository();
        $this->searchRepository = new SearchRepository();
    }

    //获取首页数据
    public function getHomePageData(Request $request){
        $uid = Auth::id();
        $data = Cache::get(CacheKey::HOMEPAGE_DATA . $uid);
        if($data && count($data) > 0){
            return $this->returnData($data);
        }
        //首页顶部轮播图
        $flash_pics1 = $this->appConfigRepository->getHomePlayback1();
        //首页中间轮播图
        $flash_pics2 = $this->appConfigRepository->getHomePlayback2();
        //顶部孕期tag
        $top_tags = $this->tagRepository->getPregTags();
        //热门主题tag
        $index_tags = $this->tagRepository->getHomeTags();
        //权威医生和活动
        $cat_activity = $this->appConfigRepository->getHomeActivity();
        //最热课程
        $hot_course = $this->courseRepository->getHotCourse($uid);
        //最新课程
        $new_course = $this->courseRepository->getNewCourse($uid);
        //推荐课程
        $recommend_course = $this->courseRepository->getCourseRecommend($uid);
        //热门搜索标签
        $search_tags = $this->searchRepository->getSearchTag();
        $data = [
            'flashPics1' => $flash_pics1,
            'flashPics2' => $flash_pics2,
            'top_tags' => $top_tags['data'],
            'index_tags' => $index_tags['data'],
            'search_tags' => $search_tags['data'],
            'hotClass' => $hot_course['data'],
            'newClass' => $new_course['data'],
            'recomClass' => $recommend_course['data'],
            'cat_activity' => $cat_activity
        ];
        Cache::put(CacheKey::HOMEPAGE_DATA . $uid, $data, 5);
        return $this->returnData($data);
    }

    //获取全部页面数据
    public function getAllPageData(Request $request){
        $type = $request->input('type');
        $time = $request->input('time');
        $tag = $request->input('tag');
        $page = $request->input('page');
        $page_size = $request->input('page_size');
        return $this->courseRepository->getAllPageData($type, $time, $tag, $page, $page_size);
    }

    //我的页面的数据
    public function getMyPageData(Request $request){
        //听课时长
        $listen = (new CourseListenRepository())->getAllListen($this->uid);
        //mq
        $uid = Auth::id();
        $mq = MqService::getUserMq($uid);
        //连续签到天数
        $user = User::where('id', $uid)->first();
        $sign_days = $user ? $user->sign_days : 0;
        //每日任务
        $task = (new TaskRepository())->getTask($this->uid);
        $task = $task['data'];
        $data = (new UserRepository())->getUserInfo();
        $advertise = Advertise::getAdvertise(Advertise::POSITION_MINE);
        $data = array_merge($data, [
            'listen' => $listen,
            'mq' => $mq,
            'sign_days' => $sign_days,
            'task' => $task,
            'advertise' => $advertise
        ]);
        return $this->returnData($data);
    }

    /*
     * 获取小程序首页数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMiniHomePageData(Request $request){
        $user = Auth::user();
        $uid = $user->id;
        $pregdate = $request->input('pregdate', $user->pregdate);

        //首页顶部轮播图
        $flash_pics1 = $this->appConfigRepository->getHomePlayback1();
        //顶部孕期tag
        $top_tags = $this->tagRepository->getPregTags();
        //热门主题tag
        $index_tags = $this->tagRepository->getHomeTags();
        //权威医生和活动
        $cat_activity = $this->appConfigRepository->getHomeActivity();
        //新的小程序的权威医生和活动
        $new_cat_activity = $this->appConfigRepository->getMiniHomeActivity();
        //推荐课程
        $recommend_course = $this->courseRepository->getCourseRecommend($uid);

        $data = [
            'flash_pics1' => $flash_pics1,
            'top_tags' => $top_tags['data'],
            'index_tags' => $index_tags['data'],
            'cat_activity' => $cat_activity,
            'new_cat_activity' => $new_cat_activity,
            'recommend_course' => $recommend_course['data']
        ];

        //获取孕期提醒的数据
        $preg_data = (new Pregnotice())->getHome($pregdate);
        $data['preg_data'] = $preg_data;
        return $this->returnData($data);
    }
}